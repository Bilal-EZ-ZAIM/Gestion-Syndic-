import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:fluttertoast/fluttertoast.dart';

class ResidencePage extends StatefulWidget {
  @override
  _ResidencePageState createState() => _ResidencePageState();
}

class _ResidencePageState extends State<ResidencePage> {
  final String baseUrl = 'http://127.0.0.1:8000/api/resedences';
  List<dynamic> residences = [];
  TextEditingController searchController = TextEditingController();
  TextEditingController nameController = TextEditingController();
  TextEditingController emailController = TextEditingController();
  TextEditingController phoneController = TextEditingController();
  TextEditingController apartmentNumberController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  String errorMessage = '';
  Map<String, String> fieldErrors = {};

  @override
  void initState() {
    super.initState();
    fetchResidences();
  }

  Future<String?> getToken() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? token = prefs.getString('token');
    print('Token: $token');
    return token;
  }

  Future<void> fetchResidences() async {
    String? token = await getToken();
    if (token == null) {
      Fluttertoast.showToast(msg: 'No token found');
      return;
    }

    try {
      final response = await http.get(
        Uri.parse(baseUrl),
        headers: {
          'Authorization': 'Bearer $token',
        },
      );

      print('Fetch Residences Response: ${response.body}');

      if (response.statusCode == 200) {
        setState(() {
          residences = jsonDecode(response.body)['residence'];
          print('Residences: $residences');
        });
      } else {
        Fluttertoast.showToast(msg: 'Failed to load residences');
      }
    } catch (error) {
      Fluttertoast.showToast(msg: 'Error: $error');
    }
  }

  Future<void> addResidence() async {
    await _handleResidenceAction('add');
  }

  Future<void> updateResidence(int id) async {
    await _handleResidenceAction('update', id: id);
  }

  Future<void> deleteResidence(int id) async {
    String? token = await getToken();
    if (token == null) {
      Fluttertoast.showToast(msg: 'No token found');
      return;
    }

    try {
      final response = await http.delete(
        Uri.parse('$baseUrl/$id'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      );

      print('Delete Residence Response: ${response.body}');

      if (response.statusCode == 200) {
        await fetchResidences();
        Fluttertoast.showToast(msg: 'Residence deleted successfully');
      } else {
        Fluttertoast.showToast(msg: 'Failed to delete residence');
      }
    } catch (error) {
      Fluttertoast.showToast(msg: 'Error: $error');
    }
  }

  Future<void> _handleResidenceAction(String action, {int? id}) async {
    String? token = await getToken();
    if (token == null) {
      Fluttertoast.showToast(msg: 'No token found');
      return;
    }

    final url = action == 'add' ? baseUrl : '$baseUrl/$id';
    final method = action == 'add' ? http.post : http.put;

    try {
      final response = await method(
        Uri.parse(url),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'name': nameController.text,
          'email': action == 'add' ? emailController.text : null,
          'password': action == 'add' && passwordController.text.isNotEmpty
              ? passwordController.text
              : null,
          'phone': phoneController.text,
          'apartment_number': apartmentNumberController.text,
        }),
      );

      print('${action.capitalize()} Residence Response: ${response.body}');

      if (response.statusCode == 201 || response.statusCode == 200) {
        // تأكد من أنك تقوم بتحديث البيانات بشكل صحيح
        setState(() {
          fetchResidences();
        });
        Fluttertoast.showToast(
            msg: action == 'add'
                ? 'Residence added successfully'
                : 'Residence updated successfully');
        Navigator.pop(context);
      } else {
        setState(() {
          errorMessage = '';
          fieldErrors = {};
          final errors = jsonDecode(response.body)['errors'];
          errors.forEach((key, value) {
            fieldErrors[key] = value.join(' ');
          });
        });
      }
    } catch (error) {
      Fluttertoast.showToast(msg: 'Error: $error');
    }
  }

  void _showDialog({int? id}) {
    final isUpdate = id != null;

    if (isUpdate) {
      final residence = residences.firstWhere((res) => res['id'] == id);
      nameController.text = residence['name'];
      phoneController.text = residence['phone'];
      apartmentNumberController.text = residence['apartment_number'];
    } else {
      nameController.clear();
      emailController.clear();
      phoneController.clear();
      apartmentNumberController.clear();
      passwordController.clear();
      errorMessage = '';
      fieldErrors.clear();
    }

    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: Text(isUpdate ? 'Update Residence' : 'Add Residence'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              _buildTextField(
                  controller: nameController,
                  label: 'Name',
                  error: fieldErrors['name']),
              if (!isUpdate)
                _buildTextField(
                    controller: emailController,
                    label: 'Email',
                    error: fieldErrors['email']),
              _buildTextField(
                  controller: phoneController,
                  label: 'Phone',
                  error: fieldErrors['phone']),
              _buildTextField(
                  controller: apartmentNumberController,
                  label: 'Apartment Number',
                  error: fieldErrors['apartment_number']),
              if (!isUpdate)
                _buildTextField(
                    controller: passwordController,
                    label: 'Password',
                    obscureText: true,
                    error: fieldErrors['password']),
              if (errorMessage.isNotEmpty)
                Padding(
                  padding: const EdgeInsets.symmetric(vertical: 8.0),
                  child: Text(
                    errorMessage,
                    style: TextStyle(color: Colors.red),
                  ),
                ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: Text('Cancel'),
            ),
            TextButton(
              onPressed: () {
                if (isUpdate) {
                  updateResidence(id!);
                } else {
                  addResidence();
                }
              },
              child: Text(isUpdate ? 'Update' : 'Add'),
            ),
          ],
        );
      },
    );
  }

  TextField _buildTextField(
      {required TextEditingController controller,
      required String label,
      bool obscureText = false,
      String? error}) {
    return TextField(
      controller: controller,
      decoration: InputDecoration(
        labelText: label,
        errorText: error,
      ),
      obscureText: obscureText,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Residence Page'),
        actions: [
          PopupMenuButton<String>(
            onSelected: (value) {
              if (value == 'home') {
                Navigator.pushNamed(context, '/home');
              } else if (value == 'maintenances') {
                Navigator.pushNamed(context, '/maintenances');
              }
            },
            itemBuilder: (BuildContext context) {
              return {'home', 'maintenances'}.map((String choice) {
                return PopupMenuItem<String>(
                  value: choice,
                  child: Text(choice),
                );
              }).toList();
            },
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: TextField(
              controller: searchController,
              decoration: InputDecoration(
                labelText: 'Search',
                border: OutlineInputBorder(),
              ),
              onChanged: (value) {
                setState(() {});
              },
            ),
          ),
          Expanded(
            child: residences.isEmpty
                ? Center(child: CircularProgressIndicator())
                : ListView.builder(
                    itemCount: residences.length,
                    itemBuilder: (context, index) {
                      final residence = residences[index];
                      final name = residence['name'].toString().toLowerCase();
                      if (name.contains(searchController.text.toLowerCase())) {
                        return Card(
                          margin:
                              EdgeInsets.symmetric(vertical: 8, horizontal: 16),
                          child: ListTile(
                            title: Text(residence['name']),
                            subtitle: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text('Email: ${residence['email']}'),
                                Text('Phone: ${residence['phone']}'),
                                Text(
                                    'Apartment Number: ${residence['apartment_number']}'),
                              ],
                            ),
                            trailing: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                IconButton(
                                  icon: Icon(Icons.edit),
                                  onPressed: () {
                                    _showDialog(id: residence['id']);
                                  },
                                ),
                                IconButton(
                                  icon: Icon(Icons.delete),
                                  onPressed: () {
                                    deleteResidence(residence['id']);
                                  },
                                ),
                              ],
                            ),
                          ),
                        );
                      }
                      return Container();
                    },
                  ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          _showDialog();
        },
        child: Icon(Icons.add),
      ),
    );
  }
}

extension StringExtension on String {
  String capitalize() {
    return "${this[0].toUpperCase()}${substring(1)}";
  }
}
