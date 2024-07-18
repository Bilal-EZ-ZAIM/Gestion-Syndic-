import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';

class HoaScreen extends StatefulWidget {
  @override
  _HoaScreenState createState() => _HoaScreenState();
}

class _HoaScreenState extends State<HoaScreen> {
  Hoa? hoa;
  bool _isLoading = true;
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _descriptionController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _pricePerMonthController =
      TextEditingController();
  final TextEditingController _totalController = TextEditingController();
  File? _imageFile;

  @override
  void initState() {
    super.initState();
    _fetchHoaData();
  }

  Future<void> _fetchHoaData() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? token = prefs.getString('token');

    if (token != null) {
      final response = await http.get(
        Uri.parse('http://127.0.0.1:8000/api/getHOA'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        setState(() {
          hoa = Hoa.fromJson(data);
          _nameController.text = hoa!.name;
          _descriptionController.text = hoa!.description;
          _addressController.text = hoa!.address;
          _pricePerMonthController.text = hoa!.pricePerMonth.toString();
          _totalController.text = hoa!.total.toString();
          _isLoading = false;
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(
          content: Text('Failed to fetch HOA data.'),
        ));
      }
    }
  }

  Future<void> _updateHoaData() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? token = prefs.getString('token');

    if (token != null && hoa != null) {
      Map<String, dynamic> updatedData = {
        'name': _nameController.text,
        'description': _descriptionController.text,
        'address': _addressController.text,
        'price_per_month': int.parse(_pricePerMonthController.text),
        'total': int.parse(_totalController.text),
      };

      if (_imageFile != null) {
        String base64Image = base64Encode(_imageFile!.readAsBytesSync());
        updatedData['image'] = base64Image;
      }

      final response = await http.put(
        Uri.parse('http://127.0.0.1:8000/api/hoas/${hoa!.userId}'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(updatedData),
      );

      if (response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(
          content: Text('HOA updated successfully.'),
        ));
        _fetchHoaData();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(
          content: Text('Failed to update HOA data.'),
        ));
      }
    }
  }

  Future<void> _pickImage(ImageSource source) async {
    final pickedFile = await ImagePicker().getImage(source: source);

    setState(() {
      if (pickedFile != null) {
        _imageFile = File(pickedFile.path);
      }
    });
  }

  void _showEditDialog() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: Text('Edit HOA'),
          content: SingleChildScrollView(
            child: Column(
              children: <Widget>[
                if (_imageFile != null)
                  Image.file(
                    _imageFile!,
                    height: 200,
                    width: double.infinity,
                    fit: BoxFit.cover,
                  ),
                ElevatedButton(
                  onPressed: () => _pickImage(ImageSource.gallery),
                  child: Text('Pick Image'),
                ),
                TextField(
                  controller: _nameController,
                  decoration: InputDecoration(labelText: 'Name'),
                ),
                TextField(
                  controller: _descriptionController,
                  decoration: InputDecoration(labelText: 'Description'),
                ),
                TextField(
                  controller: _addressController,
                  decoration: InputDecoration(labelText: 'Address'),
                ),
                TextField(
                  controller: _pricePerMonthController,
                  decoration: InputDecoration(labelText: 'Price per Month'),
                  keyboardType: TextInputType.number,
                ),
                TextField(
                  controller: _totalController,
                  decoration: InputDecoration(labelText: 'Total'),
                  keyboardType: TextInputType.number,
                ),
              ],
            ),
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text('Cancel'),
            ),
            ElevatedButton(
              onPressed: () {
                _updateHoaData();
                Navigator.of(context).pop();
              },
              child: Text('Update'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('HOA Details'),
        actions: [
          PopupMenuButton<String>(
            onSelected: (value) {
              if (value == 'residence') {
                Navigator.pushNamed(context, '/residence');
              } else if (value == 'maintenances') {
                Navigator.pushNamed(context, '/maintenances');
              }
            },
            itemBuilder: (BuildContext context) {
              return {'residence', 'maintenances'}.map((String choice) {
                return PopupMenuItem<String>(
                  value: choice,
                  child: Text(choice),
                );
              }).toList();
            },
          ),
        ],
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : hoa == null
              ? Center(child: Text('No HOA data found.'))
              : SingleChildScrollView(
                  child: Column(
                    children: <Widget>[
                      if (hoa!.image != null)
                        Container(
                          height: 200,
                          width: double.infinity,
                          decoration: BoxDecoration(
                            image: DecorationImage(
                              image: NetworkImage(hoa!.image!),
                              fit: BoxFit.cover,
                            ),
                          ),
                        ),
                      Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: <Widget>[
                            Card(
                              child: ListTile(
                                title: Text('Name'),
                                subtitle: Text(hoa!.name),
                              ),
                            ),
                            Card(
                              child: ListTile(
                                title: Text('Description'),
                                subtitle: Text(hoa!.description),
                              ),
                            ),
                            Card(
                              child: ListTile(
                                title: Text('Address'),
                                subtitle: Text(hoa!.address),
                              ),
                            ),
                            Card(
                              child: ListTile(
                                title: Text('Price per Month'),
                                subtitle: Text('\$${hoa!.pricePerMonth}'),
                              ),
                            ),
                            Card(
                              child: ListTile(
                                title: Text('Total'),
                                subtitle: Text('\$${hoa!.total}'),
                              ),
                            ),
                            SizedBox(height: 20),
                            ElevatedButton(
                              onPressed: _showEditDialog,
                              child: Text('Edit HOA'),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
    );
  }
}

class Hoa {
  final int id;
  final String name;
  final String description;
  final String? image;
  final String address;
  final int userId;
  final int pricePerMonth;
  final int total;
  final DateTime createdAt;
  final DateTime updatedAt;

  Hoa({
    required this.id,
    required this.name,
    required this.description,
    this.image,
    required this.address,
    required this.userId,
    required this.pricePerMonth,
    required this.total,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Hoa.fromJson(Map<String, dynamic> json) {
    return Hoa(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      image: json['image'],
      address: json['address'],
      userId: json['user_id'],
      pricePerMonth: json['price_per_month'],
      total: json['total'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }
}
