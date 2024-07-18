import 'package:flutter/material.dart';
import 'login_screen.dart';
import 'hoa_screen.dart';
import 'residence_page.dart';
import 'maintenances_page.dart';
import 'hoa_form_page.dart';
import 'restricted_screen.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter Demo',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      initialRoute: '/',
      routes: {
        '/': (context) => LoginScreen(),
        '/home': (context) =>
            RestrictedScreen(child: HoaScreen(), requiredUser: 'cindik'),
        '/residence': (context) =>
            RestrictedScreen(child: ResidencePage(), requiredUser: 'cindik'),
        '/maintenances': (context) =>
            RestrictedScreen(child: MaintenancesPage(), requiredUser: 'cindik'),
        '/form': (context) => RestrictedScreen(
            child: HoaFormPage(),
            requiredUser: 'cindik',
            allowFormAccess: false),
      },
    );
  }
}
