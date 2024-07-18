import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'dart:convert';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Résidents',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: MyHomePage(),
    );
  }
}

class MyHomePage extends StatefulWidget {
  @override
  _MyHomePageState createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  final Dio _dio = Dio();
  List<Map<String, dynamic>> _residents = [];

  @override
  void initState() {
    super.initState();
    _fetchResidents();
  }

  Future<void> _fetchResidents() async {
    try {
      final response = await _dio.get('YOUR_GET_ENDPOINT');
      setState(() {
        _residents =
            List<Map<String, dynamic>>.from(response.data['residence']);
      });
    } catch (e) {
      print('Error fetching residents: $e');
    }
  }

  Future<void> _addResident(Map<String, dynamic> residentData) async {
    try {
      final response = await _dio.post(
        'YOUR_POST_ENDPOINT',
        data: FormData.fromMap(residentData),
        options: Options(headers: {'X-CSRF-TOKEN': 'YOUR_CSRF_TOKEN'}),
      );
      if (response.statusCode == 200) {
        setState(() {
          _residents.add(response.data['residence']);
        });
      }
    } catch (e) {
      print('Error adding resident: $e');
    }
  }

  Future<void> _updateResident(
      int id, Map<String, dynamic> residentData) async {
    try {
      final response = await _dio.post(
        'YOUR_UPDATE_ENDPOINT/$id',
        data: FormData.fromMap(residentData),
        options: Options(headers: {'X-CSRF-TOKEN': 'YOUR_CSRF_TOKEN'}),
      );
      if (response.statusCode == 200) {
        setState(() {
          final index = _residents.indexWhere((element) => element['id'] == id);
          _residents[index] = response.data['resident'];
        });
      }
    } catch (e) {
      print('Error updating resident: $e');
    }
  }

  Future<void> _deleteResident(int id) async {
    try {
      final response = await _dio.delete(
        'YOUR_DELETE_ENDPOINT/$id',
        options: Options(headers: {'X-CSRF-TOKEN': 'YOUR_CSRF_TOKEN'}),
      );
      if (response.statusCode == 200) {
        setState(() {
          _residents.removeWhere((element) => element['id'] == id);
        });
      }
    } catch (e) {
      print('Error deleting resident: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Page des Résidents'),
      ),
      body: Column(
        children: [
          Container(
            padding: EdgeInsets.all(30),
            decoration: BoxDecoration(
              image: DecorationImage(
                image: AssetImage('chemin/vers/votre/image.jpg'),
                fit: BoxFit.cover,
              ),
            ),
            child: Column(
              children: [
                Text(
                  'Page des Résidents',
                  style: TextStyle(fontSize: 24, color: Colors.white, shadows: [
                    Shadow(
                      blurRadius: 4,
                      color: Colors.black.withOpacity(0.5),
                      offset: Offset(2, 2),
                    ),
                  ]),
                ),
                Text(
                  'Gérez facilement les informations des résidents.',
                  style: TextStyle(fontSize: 16, color: Colors.white, shadows: [
                    Shadow(
                      blurRadius: 4,
                      color: Colors.black.withOpacity(0.5),
                      offset: Offset(2, 2),
                    ),
                  ]),
                ),
              ],
            ),
          ),
          Expanded(
            child: ListView.builder(
              itemCount: _residents.length,
              itemBuilder: (context, index) {
                final resident = _residents[index];
                return ListTile(
                  title: Text(resident['name']),
                  subtitle: Text(resident['email']),
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      IconButton(
                        icon: Icon(Icons.edit),
                        onPressed: () {
                          // Add update functionality here
                        },
                      ),
                      IconButton(
                        icon: Icon(Icons.delete),
                        onPressed: () {
                          _deleteResident(resident['id']);
                        },
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          // Add add functionality here
        },
        child: Icon(Icons.add),
      ),
    );
  }
}
