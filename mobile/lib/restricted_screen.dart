import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class RestrictedScreen extends StatelessWidget {
  final Widget child;
  final String requiredUser;
  final bool allowFormAccess;

  RestrictedScreen(
      {required this.child,
      required this.requiredUser,
      this.allowFormAccess = true});

  Future<Map<String, dynamic>?> _getCurrentUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? userJson = prefs.getString('user');
    if (userJson != null) {
      Map<String, dynamic> user = jsonDecode(userJson);
      print('Current User: $user'); // طباعة اسم المستخدم الحالي
      return user;
    }
    return null;
  }

  @override
  Widget build(BuildContext context) {
    print(
        'Required User: $requiredUser'); // طباعة قيمة requiredUser في وحدة التحكم
    return FutureBuilder<Map<String, dynamic>?>(
      future: _getCurrentUser(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return Scaffold(
            appBar: AppBar(
              title: Text('Loading'),
            ),
            body: Center(
              child: CircularProgressIndicator(),
            ),
          );
        } else if (snapshot.hasError) {
          return Scaffold(
            appBar: AppBar(
              title: Text('Error'),
            ),
            body: Center(
              child: Text('An error occurred'),
            ),
          );
        } else if (snapshot.hasData && snapshot.data != null) {
          Map<String, dynamic> currentUser = snapshot.data!;
          print(
              'Current User: ${currentUser['name']}'); // طباعة اسم المستخدم الحالي

          if (currentUser['role'] == requiredUser) {
            if (currentUser['hoa_id'] != null &&
                !allowFormAccess &&
                ModalRoute.of(context)?.settings.name == '/form') {
              WidgetsBinding.instance.addPostFrameCallback((_) {
                Navigator.pushReplacementNamed(context, '/home');
              });
              return Scaffold(
                body: Center(
                  child: CircularProgressIndicator(),
                ),
              );
            }
            return child;
          } else {
            return Scaffold(
              appBar: AppBar(
                title: Text('Access Denied'),
              ),
              body: Center(
                child: Text('You do not have permission to access this page.'),
              ),
            );
          }
        } else {
          return Scaffold(
            appBar: AppBar(
              title: Text('No User Found'),
            ),
            body: Center(
              child: Text('No user is currently logged in.'),
            ),
          );
        }
      },
    );
  }
}
