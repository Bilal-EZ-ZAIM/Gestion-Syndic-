import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:fluttertoast/fluttertoast.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Residence Management',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: ResidencePage(),
    );
  }
}

class ResidencePage extends StatefulWidget {
  @override
  _ResidencePageState createState() => _ResidencePageState();
}

class _ResidencePageState extends State<ResidencePage> {
  final String baseUrl = 'http://127.0.0.1:8000/api/resedences';
  final FlutterSecureStorage storage = FlutterSecureStorage();

  List<dynamic> residences = [];
  TextEditingController searchController = TextEditingController();
  TextEditingController nameController = TextEditingController();
  TextEditingController emailController = TextEditingController();
  TextEditingController phoneController = TextEditingController();
  TextEditingController apartmentNumberController = TextEditingController();
  TextEditingController passwordController =
      TextEditingController(); // New password controller

  @override
  void initState() {
    super.initState();
    fetchResidences();
  }

  Future<String?> getToken() async {
    return await storage.read(key: 'token');
  }

  Future<void> fetchResidences() async {
    String? token = await getToken();
    final response = await http.get(
      Uri.parse('$baseUrl'),
      headers: {
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      setState(() {
        residences = jsonDecode(response.body)['residence'];
      });
    } else {
      Fluttertoast.showToast(msg: 'Failed to load residences');
    }
  }

  Future<void> addResidence() async {
    String? token = await getToken();
    final response = await http.post(
      Uri.parse('$baseUrl'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        'name': nameController.text,
        'email': emailController.text,
        'password': passwordController.text, // Send the actual password
        'phone': phoneController.text,
        'apartment_number': apartmentNumberController.text,
      }),
    );

    if (response.statusCode == 200) {
      fetchResidences();
      Fluttertoast.showToast(msg: 'Residence added successfully');
      Navigator.pop(context);
    } else {
      Fluttertoast.showToast(msg: 'Failed to add residence');
    }
  }

  Future<void> updateResidence(int id) async {
    String? token = await getToken();
    final response = await http.put(
      Uri.parse('$baseUrl/$id'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        'name': nameController.text,
        'email': emailController.text,
        'phone': phoneController.text,
        'apartment_number': apartmentNumberController.text,
        'password':
            passwordController.text, // Send the actual password if needed
      }),
    );

    if (response.statusCode == 200) {
      fetchResidences();
      Fluttertoast.showToast(msg: 'Residence updated successfully');
      Navigator.pop(context);
    } else {
      Fluttertoast.showToast(msg: 'Failed to update residence');
    }
  }

  Future<void> deleteResidence(int id) async {
    String? token = await getToken();
    final response = await http.delete(
      Uri.parse('$baseUrl/$id'),
      headers: {
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      fetchResidences();
      Fluttertoast.showToast(msg: 'Residence deleted successfully');
    } else {
      Fluttertoast.showToast(msg: 'Failed to delete residence');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Residence Management'),
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
            child: ListView.builder(
              itemCount: residences.length,
              itemBuilder: (context, index) {
                final residence = residences[index];
                final name = residence['name'].toString().toLowerCase();
                if (name.contains(searchController.text.toLowerCase())) {
                  return ListTile(
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
                            nameController.text = residence['name'];
                            emailController.text = residence['email'];
                            phoneController.text = residence['phone'];
                            apartmentNumberController.text =
                                residence['apartment_number'];
                            passwordController.text =
                                ''; // Clear the password field
                            showDialog(
                              context: context,
                              builder: (context) {
                                return AlertDialog(
                                  title: Text('Update Residence'),
                                  content: Column(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      TextField(
                                        controller: nameController,
                                        decoration: InputDecoration(
                                          labelText: 'Name',
                                        ),
                                      ),
                                      TextField(
                                        controller: emailController,
                                        decoration: InputDecoration(
                                          labelText: 'Email',
                                        ),
                                      ),
                                      TextField(
                                        controller: phoneController,
                                        decoration: InputDecoration(
                                          labelText: 'Phone',
                                        ),
                                      ),
                                      TextField(
                                        controller: apartmentNumberController,
                                        decoration: InputDecoration(
                                          labelText: 'Apartment Number',
                                        ),
                                      ),
                                      TextField(
                                        controller: passwordController,
                                        decoration: InputDecoration(
                                          labelText: 'Password',
                                          hintText: 'Leave blank if unchanged',
                                        ),
                                        obscureText: true,
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
                                        updateResidence(residence['id']);
                                      },
                                      child: Text('Update'),
                                    ),
                                  ],
                                );
                              },
                            );
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
          nameController.clear();
          emailController.clear();
          phoneController.clear();
          apartmentNumberController.clear();
          passwordController.clear(); // Clear the password field
          showDialog(
            context: context,
            builder: (context) {
              return AlertDialog(
                title: Text('Add Residence'),
                content: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    TextField(
                      controller: nameController,
                      decoration: InputDecoration(
                        labelText: 'Name',
                      ),
                    ),
                    TextField(
                      controller: emailController,
                      decoration: InputDecoration(
                        labelText: 'Email',
                      ),
                    ),
                    TextField(
                      controller: phoneController,
                      decoration: InputDecoration(
                        labelText: 'Phone',
                      ),
                    ),
                    TextField(
                      controller: apartmentNumberController,
                      decoration: InputDecoration(
                        labelText: 'Apartment Number',
                      ),
                    ),
                    TextField(
                      controller: passwordController,
                      decoration: InputDecoration(
                        labelText: 'Password',
                      ),
                      obscureText: true,
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
                      addResidence();
                    },
                    child: Text('Add'),
                  ),
                ],
              );
            },
          );
        },
        child: Icon(Icons.add),
      ),
    );
  }
}
