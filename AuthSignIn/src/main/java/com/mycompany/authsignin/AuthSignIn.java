package com.mycompany.authsignin;

//This would be the demo class

import java.util.Scanner;

public class AuthSignIn {
    public static void main(String[] args) {
        SignIn signIn = new SignIn();

        Scanner scanner = new Scanner(System.in);
        
        // Registration flow
        System.out.println("Welcome to the Sign-In System");
        System.out.println("Username: ");
        String newUsername = scanner.nextLine();
        
        // Check if username already exists
        if (signIn.userExists(newUsername)){
            System.out.println("Username already exists. Please choose a different username");
            return;
        }
        
        System.out.println("Enter a password: ");
        String newPassword = scanner.nextLine();
        signIn.registerUser(newUsername, newPassword);
        System.out.println("Registration Successful!");
        
        // Sign in Flow

        while (true) {
            System.out.println("Welcome to the Sign-In System");
            System.out.print("Username: ");
            String username = scanner.nextLine();

            System.out.print("Password: ");
            String password = scanner.nextLine();

            if (signIn.signIn(username, password)) {
                System.out.println("Sign-in successful!");
                // Additional actions for a signed-in user can go here
                break; // Exit the loop when the user signs in successfully
            } else {
                System.out.println("Invalid credentials. Please try again.");
            }
        }
        
        // SMS Authentication Flow
        System.out.println("SMS Authentication");
        System.out.println("Please enter your phone number: ");
        String newNumber = scanner.nextLine();
        signIn.initiateSmsAuthentication(newNumber);
        System.out.println("Enter the received SMS verification code: ");
        String smsCode = scanner.nextLine();
        
        if (signIn.signInWithSms(newUsername, smsCode) == true){
            System.out.println("SMS Authentication successful!");
        }else{
            System.out.println("SMS Authentication failed. Exiting.");
            return;
        }
        // Two-Factor Authentication Flow
        System.out.println("Two-Factor Authentication");
        
        // Generate and send a two-factor authentication code (If implemented into a real program, this would be replaced with a way to send a code to their email)
        System.out.println("Enter the received two-factor authentication code: ");
        String twoFactorCode = scanner.nextLine();
        if(signIn.signInWithTwoFactor(newUsername, newPassword, twoFactorCode)){
            System.out.println("Two-Factor Authentication successful!");
        }else{
            System.out.println("Two-Factor Authentication failed. Exiting");
            return;
        }
    }
}
