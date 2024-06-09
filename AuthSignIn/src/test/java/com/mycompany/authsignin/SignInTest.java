package com.mycompany.authsignin;
/**
 *
 * @author jacka
 */
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.BeforeEach;

class SignInTest {
    private SignIn signIn;
    
    @BeforeEach
    public void setUp(){
        signIn = new SignIn();
    }
    
    @Test
    void validateCorrectCredentials(){
        assertTrue(signIn.validateCredentials("user1", "password1")); // Original code
        // assertFalse(signIn.validateCredentials("user1", "password1")); // Uncomment to test mutated code
    }
    
    @Test
    void validateIncorrectCredentials(){
        assertFalse(signIn.validateCredentials("user1", "wrongpassword"));
        // assertTrue(signIn.validateCredentials("user1", "wrongpassword")); // Uncomment to test mutated code
    }
    
    @Test
    void validateNonexistentUser(){
        assertFalse(signIn.validateCredentials("nonexistentuser", "password"));
    }
    
    @Test
    void signInSuccessful(){
        signIn.signIn("user1", "password1");
        //assertTrue(signIn.isUserLoggedIn("user1"));
        assertFalse(signIn.isUserLoggedIn("user1")); // Uncomment to test mutation 1
        // assertTrue(signIn.isUserLoggedIn("user1")); // Uncomment to test mutation 2
        // assertFalse(signIn.isUserLoggedIn("user1")); // Uncomment to test mutation 3
    }
    @Test
    void signInInvalidCredentials(){
        assertFalse(signIn.signIn("user1", "wrongpassword"));
        assertFalse(signIn.isUserLoggedIn("user1"));
    }
    
    @Test
    void sendSmsCodeSuccessful(){
        assertTrue(signIn.initiateSmsAuthentication("user1"));
        // With a real world example, you would need to verify the SMS sending success more manually
    }
    
    @Test
    void signInWithSmsSuccessful(){
        signIn.initiateSmsAuthentication("User1");
        String verificationCode = signIn.getVerificationCode("User1");
        
        assertTrue(signIn.signInWithSms("user1", verificationCode));
        assertTrue(signIn.isUserLoggedIn("user1"));
    }
    
    @Test
    void signInWithSmsInvalidCode(){
        assertFalse(signIn.signInWithSms("user1", "invalidCode"));
        assertFalse(signIn.isUserLoggedIn("user1"));
    }
}
