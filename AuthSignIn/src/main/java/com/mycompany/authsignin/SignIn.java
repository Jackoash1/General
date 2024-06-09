package com.mycompany.authsignin;

/**
 *
 * @author jacka
 */

import java.util.HashMap;
import java.util.Map;
import java.util.Random;

public class SignIn {
    private final Map<String, String> userCredentials = new HashMap<>();
    private final Map<String, Boolean> loggedInUsers = new HashMap<>();
    private final Map<String, String> verificationCodes = new HashMap<>();
    
    public void registerUser(String username, String password){
        // Logic to store the new user's credentials, for example, in to a database
        userCredentials.put(username, password);
    }
    
    public SignIn(){
        // Initialises user credentials, in a real-world program, this information would be safely stored in a database
        userCredentials.put("user1", "password1");
        userCredentials.put("user2", "password2");
        loggedInUsers.put("user1", Boolean.TRUE);
        loggedInUsers.put("user2", Boolean.TRUE);
    }   
    
    public boolean signIn(String username, String password){
        if (validateCredentials(username, password)) {
            return true;
        }else{
            return false;
        }
    }
    
    public boolean sendCode(String username){
        String verificationCode =  generateVerificationCode();
        verificationCodes.put(username, verificationCode);
        
        System.out.println("SMS verification code for " + username + ": " + verificationCode);
        
        return true;
    }
    
    public boolean signInWithTwoFactor(String username, String password, String twoFactorCode) {
        if (validateCredentials(username, password) && validateTwoFactorCode(username, twoFactorCode)) {
            loggedInUsers.put(username, true);
            return true;
        } else {
            return false;
        }
    }
    
    private boolean validateTwoFactorCode(String username, String twoFactorCode) {
        // Implement logic to validate the two-factor code, e.g., compare with stored codes
        // For simplicity, this example assumes a basic validation
        return twoFactorCode.equals("123456"); // Replace with your actual validation logic
    }
    
    // Generate a random 6-digit verification code
    private String generateVerificationCode(){
        // Generating and returning a random 6-digit code
        return String.format("%06d", new Random().nextInt(1000000));
    }
    
    public String getVerificationCode(String username){
        return verificationCodes.get(username);
    }
    
    // Validate the entered verification code
    private boolean validateVerificationCode(String enteredCode, String expectedCode){
        // Compare entered code with expected code
        return enteredCode.equals(expectedCode);
    }
    
    public boolean signInWithSms(String username, String verificationCode){
        // Validate the verification code
        if (validateSmsCode(username, verificationCode)){
            loggedInUsers.put(username, true);
            return true;
        }else{
            return false;
        }
    }
    
    private boolean validateSmsCode(String username, String verificationCode){
        // validate the entered verification code
        String storedCode = verificationCodes.get(username);
        return storedCode != null && storedCode.equals(verificationCode);
    }
    
     // Integrate with a third party SMS service to send verification codes
    private boolean sendVerificationCode(String phoneNumber, String code){
        // Third party code for SMS goes here
        // Return true if SMS is sent successfully, false otherwise
        System.out.println("SMS sent to " + phoneNumber + " with code: " + code);
        return true; // Replace with actual implementation
    }
    
    // New method to initiate SMS authentication
    public boolean initiateSmsAuthentication(String phoneNumber){
        String verificationCode = generateVerificationCode();
        
        // Store the verification code(in memory storage; replace with a secure storage mechanism)
        verificationCodes.put(phoneNumber, verificationCode);
        
        // Send SMS with verification code
        boolean smsSent = sendVerificationCode(phoneNumber, verificationCode);
        
        return smsSent;
    }
    
    // New method to complete SMS authentication
    public boolean completeSmsAuthentication(String phoneNumber, String enteredCode){
        if (verificationCodes.containsKey(phoneNumber)){
            String expectedCode = verificationCodes.get(phoneNumber);
            if (validateVerificationCode(enteredCode, expectedCode)){
                loggedInUsers.put(phoneNumber, true);
                return true;
            }
        }
        return false;
    }
    
    // Checking if the username is already in the userCredentials map
    public boolean userExists(String username){
        return userCredentials.containsKey(username);
    }
    
    public boolean isUserLoggedIn(String username){
        return loggedInUsers.getOrDefault(username, true);
        // return false; // Uncomment to test mutation
    }
    
    protected boolean validateCredentials(String username, String password){
        //like earlier, this would instead be replaced by querying a database for the user credentials
        return userCredentials.containsKey(username) && userCredentials.get(username).equals(password);
    }
}
