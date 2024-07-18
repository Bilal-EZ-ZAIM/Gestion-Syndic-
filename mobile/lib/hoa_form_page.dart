import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class HoaFormPage extends StatefulWidget {
  @override
  _HoaFormPageState createState() => _HoaFormPageState();
}

class _HoaFormPageState extends State<HoaFormPage> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _descriptionController = TextEditingController();
  final TextEditingController _totalController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _priceController = TextEditingController();
  bool _isLoading = false;

  String? _bearerToken;

  @override
  void initState() {
    super.initState();
    _getBearerToken();
  }

  Future<void> _getBearerToken() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      _bearerToken = prefs.getString('token');
    });
  }

  Future<void> _submitForm() async {
    if (_formKey.currentState?.validate() ?? false) {
      setState(() {
        _isLoading = true;
      });

      try {
        final response = await http.post(
          Uri.parse('http://127.0.0.1:8000/api/hoa/store'),
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization':
                'Bearer $_bearerToken', // Include bearer token here
          },
          body: jsonEncode({
            'name': _nameController.text,
            'description': _descriptionController.text,
            'total': _totalController.text,
            'address': _addressController.text,
            'price_per_month': _priceController.text,
          }),
        );

        final responseData = jsonDecode(response.body);
        if (response.statusCode == 200) {
          // Handle success
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Success: ${responseData['success']}')),
          );
          // Navigate to the desired page
          Navigator.pushNamed(context, '/getViewResedence');
        } else {
          // Handle validation errors
          final errors = responseData['errors'] as Map<String, dynamic>;
          errors.forEach((field, messages) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('$field: ${messages[0]}')),
            );
          });
        }
      } catch (error) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: $error')),
        );
      } finally {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Form Layouts'),
      ),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              HeroSection(),
              TextFormField(
                controller: _nameController,
                decoration: InputDecoration(labelText: 'Name'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a name';
                  }
                  return null;
                },
              ),
              TextFormField(
                controller: _descriptionController,
                decoration: InputDecoration(labelText: 'Description'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a description';
                  }
                  return null;
                },
              ),
              TextFormField(
                controller: _totalController,
                decoration: InputDecoration(labelText: 'Total'),
                keyboardType: TextInputType.number,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a total';
                  }
                  return null;
                },
              ),
              TextFormField(
                controller: _addressController,
                decoration: InputDecoration(labelText: 'Address'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter an address';
                  }
                  return null;
                },
              ),
              TextFormField(
                controller: _priceController,
                decoration: InputDecoration(labelText: 'Price Per Month'),
                keyboardType: TextInputType.number,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a price per month';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20.0),
              _isLoading
                  ? CircularProgressIndicator()
                  : ElevatedButton(
                      onPressed: _submitForm,
                      child: Text('Submit'),
                    ),
            ],
          ),
        ),
      ),
    );
  }
}

class HeroSection extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(vertical: 50.0),
      decoration: BoxDecoration(
        image: DecorationImage(
          image: AssetImage('assets/images/your_image.jpg'),
          fit: BoxFit.cover,
        ),
      ),
      child: Center(
        child: Column(
          children: [
            Text(
              'Bienvenue sur notre page de création de HOA',
              style: TextStyle(fontSize: 24.0, color: Colors.white, shadows: [
                Shadow(
                  offset: Offset(2.0, 2.0),
                  blurRadius: 3.0,
                  color: Color.fromARGB(255, 0, 0, 0),
                ),
              ]),
            ),
            Text(
              'Créez votre HOA facilement avec notre formulaire simple.',
              style: TextStyle(fontSize: 16.0, color: Colors.white, shadows: [
                Shadow(
                  offset: Offset(2.0, 2.0),
                  blurRadius: 3.0,
                  color: Color.fromARGB(255, 0, 0, 0),
                ),
              ]),
            ),
          ],
        ),
      ),
    );
  }
}

void main() {
  runApp(MaterialApp(
    home: HoaFormPage(),
    routes: {
      '/getViewResedence': (context) => GetViewResidencePage(),
    },
  ));
}

class GetViewResidencePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('View Residence'),
      ),
      body: Center(
        child: Text('This is the view residence page'),
      ),
    );
  }
}
