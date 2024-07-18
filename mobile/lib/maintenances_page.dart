import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:fluttertoast/fluttertoast.dart';

class MaintenancesPage extends StatefulWidget {
  @override
  _MaintenancesPageState createState() => _MaintenancesPageState();
}

class _MaintenancesPageState extends State<MaintenancesPage> {
  final String baseUrl = 'http://127.0.0.1:8000/api/maintenances';
  List<dynamic> maintenances = [];
  TextEditingController searchController = TextEditingController();
  TextEditingController titleController = TextEditingController();
  TextEditingController descriptionController = TextEditingController();
  TextEditingController factureController = TextEditingController();
  String errorMessage = '';
  Map<String, String> fieldErrors = {};

  @override
  void initState() {
    super.initState();
    fetchMaintenances();
  }

  Future<String?> getToken() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? token = prefs.getString('token');
    print('Token: $token');
    return token;
  }

  Future<void> fetchMaintenances() async {
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

      print('Fetch Maintenances Response: ${response.body}');

      if (response.statusCode == 200) {
        setState(() {
          maintenances = jsonDecode(response.body)['maintenances'];
        });
      } else {
        Fluttertoast.showToast(msg: 'Failed to load maintenances');
      }
    } catch (error) {
      Fluttertoast.showToast(msg: 'Error: $error');
    }
  }

  Future<void> addMaintenance() async {
    await _handleMaintenanceAction('add');
  }

  Future<void> updateMaintenance(int id) async {
    await _handleMaintenanceAction('update', id: id);
  }

  Future<void> deleteMaintenance(int id) async {
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

      print('Delete Maintenance Response: ${response.body}');

      if (response.statusCode == 200) {
        setState(() {
          maintenances.removeWhere((maintenance) => maintenance['id'] == id);
        });
        Fluttertoast.showToast(msg: 'Maintenance deleted successfully');
      } else {
        Fluttertoast.showToast(msg: 'Failed to delete maintenance');
      }
    } catch (error) {
      Fluttertoast.showToast(msg: 'Error: $error');
    }
  }

  Future<void> _handleMaintenanceAction(String action, {int? id}) async {
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
          'title': titleController.text,
          'description': descriptionController.text,
          'facture': factureController.text,
        }),
      );

      print('${action.capitalize()} Maintenance Response: ${response.body}');

      if (response.statusCode == 201 || response.statusCode == 200) {
        await fetchMaintenances();
        Fluttertoast.showToast(
            msg: action == 'add'
                ? 'Maintenance added successfully'
                : 'Maintenance updated successfully');
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
      final maintenance = maintenances.firstWhere((main) => main['id'] == id);
      titleController.text = maintenance['title'];
      descriptionController.text = maintenance['description'];
      factureController.text = maintenance['facture'];
    } else {
      titleController.clear();
      descriptionController.clear();
      factureController.clear();
      errorMessage = '';
      fieldErrors.clear();
    }

    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: Text(isUpdate ? 'Update Maintenance' : 'Add Maintenance'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              _buildTextField(
                  controller: titleController,
                  label: 'Title',
                  error: fieldErrors['title']),
              _buildTextField(
                  controller: descriptionController,
                  label: 'Description',
                  error: fieldErrors['description']),
              _buildTextField(
                  controller: factureController,
                  label: 'Facture',
                  error: fieldErrors['facture']),
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
                  updateMaintenance(id!);
                } else {
                  addMaintenance();
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
        title: Text('Maintenances Page'),
        actions: [
          PopupMenuButton<String>(
            onSelected: (value) {
              if (value == 'residence') {
                Navigator.pushNamed(context, '/residence');
              } else if (value == 'home') {
                Navigator.pushNamed(context, '/home');
              }
            },
            itemBuilder: (BuildContext context) {
              return {'residence', 'home'}.map((String choice) {
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
            child: maintenances.isEmpty
                ? Center(child: CircularProgressIndicator())
                : ListView.builder(
                    itemCount: maintenances.length,
                    itemBuilder: (context, index) {
                      final maintenance = maintenances[index];
                      final title =
                          maintenance['title'].toString().toLowerCase();
                      if (title.contains(searchController.text.toLowerCase())) {
                        return Card(
                          margin:
                              EdgeInsets.symmetric(vertical: 8, horizontal: 16),
                          child: ListTile(
                            title: Text(maintenance['title']),
                            subtitle: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                    'Description: ${maintenance['description']}'),
                                Text('Facture: ${maintenance['facture']}'),
                              ],
                            ),
                            trailing: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                IconButton(
                                  icon: Icon(Icons.edit),
                                  onPressed: () {
                                    _showDialog(id: maintenance['id']);
                                  },
                                ),
                                IconButton(
                                  icon: Icon(Icons.delete),
                                  onPressed: () {
                                    deleteMaintenance(maintenance['id']);
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
