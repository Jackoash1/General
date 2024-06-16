package uk.ac.bradford.dungeongame;

import java.awt.Point;
import java.util.ArrayList;
import java.util.Random;
import uk.ac.bradford.dungeongame.Entity.EntityType;

/**
 * The GameEngine class is responsible for managing information about the game,
 * creating levels, the player and monsters, as well as updating information
 * when a key is pressed while the game is running.
 * @author prtrundl
 */
public class GameEngine {

    /**
     * An enumeration type to represent different types of tiles that make up
     * a dungeon level. Each type has a corresponding image file that is used
     * to draw the right tile to the screen for each tile in a level. Floors are
     * open for monsters and the player to move into, walls should be impassable,
     * stairs allow the player to progress to the next level of the dungeon, and
     * chests can yield a reward when moved over.
     */
    public enum TileType {
        WALL, FLOOR, CHEST, STAIRS, HEAL
    }

    /**
     * The width of the dungeon level, measured in tiles. Changing this may
     * cause the display to draw incorrectly, and as a minimum the size of the
     * GUI would need to be adjusted.
     */
    public static final int DUNGEON_WIDTH = 25;
    
    /**
     * The height of the dungeon level, measured in tiles. Changing this may
     * cause the display to draw incorrectly, and as a minimum the size of the
     * GUI would need to be adjusted.
     */
    public static final int DUNGEON_HEIGHT = 18;
    
    /**
     * The maximum number of monsters that can be generated on a single level
     * of the dungeon. This attribute can be used to fix the size of an array
     * (or similar) that will store monsters.
     */
    public static final int MAX_MONSTERS = 40;
    
    /**
     * The chance of a wall being generated instead of a floor when generating
     * the level. 1.0 is 100% chance, 0.0 is 0% chance.
     */
    public static final double WALL_CHANCE = 0.05;

    /**
     * A random number generator that can be used to include randomised choices
     * in the creation of levels, in choosing places to spawn the player and
     * monsters, and to randomise movement and damage. This currently uses a seed
     * value of 123 to generate random numbers - this helps you find bugs by
     * giving you the same numbers each time you run the program. Remove
     * the seed value if you want different results each game.
     */
    private Random rng = new Random(123);

    /**
     * The current level number for the dungeon. As the player moves down stairs
     * the level number should be increased and can be used to increase the
     * difficulty e.g. by creating additional monsters with more health.
     */
    private int depth = 1;  //current dunegeon level

    /**
     * The GUI associated with a GameEngine object. THis link allows the engine
     * to pass level (tiles) and entity information to the GUI to be drawn.
     */
    private GameGUI gui;

    /**
     * The 2 dimensional array of tiles the represent the current dungeon level.
     * The size of this array should use the DUNGEON_HEIGHT and DUNGEON_WIDTH
     * attributes when it is created.
     */
    private TileType[][] tiles;
    
    /**
     * An ArrayList of Point objects used to create and track possible locations
     * to spawn the player and monsters.
     */
    private ArrayList<Point> spawns;

    /**
     * An Entity object that is the current player. This object stores the state
     * information for the player, including health and the current position (which
     * is a pair of co-ordinates that corresponds to a tile in the current level)
     */
    private Entity player;
    
    /**
     * An array of Entity objects that represents the monsters in the current
     * level of the dungeon. Elements in this array should be of the type Entity,
     * meaning that a monster is alive and needs to be drawn or moved, or should
     * be null which means nothing is drawn or processed for movement.
     * Null values in this array are skipped during drawing and movement processing.
     * Monsters (Entity objects) that die due to player attacks can be replaced
     * with the value null in this array which removes them from the game.
     */
    private Entity[] monsters;

    /**
     * Constructor that creates a GameEngine object and connects it with a GameGUI
     * object.
     * @param gui The GameGUI object that this engine will pass information to in
     * order to draw levels and entities to the screen.
     */
    public GameEngine(GameGUI gui) {
        this.gui = gui;
        startGame();
    }

    /**
     * Generates a new dungeon level. The method builds a 2D array of TileType values
     * that will be used to draw tiles to the screen and to add a variety of
     * elements into each level. Tiles can be floors, walls, stairs (to progress
     * to the next level of the dungeon) or chests. The method should contain
     * the implementation of an algorithm to create an interesting and varied
     * level each time it is called.
     * @return A 2D array of TileTypes representing the tiles in the current
     * level of the dungeon. The size of this array should use the width and
     * height of the dungeon.
     */
    private TileType[][] generateLevel() {
        TileType[][] x = new TileType[24][17];   //Each dimension is 1 less than the requested since arrays index at 0
        for (int i = 0; i < 24; i++){            //first loop that goes through all the rows of the array
            for (int j = 0; j < 17; j++){        //second loop that goes through all the collumns of the array
                double tileRand = Math.random(); //creates a random number between 0.0 and 1.0
                if(tileRand <= 0.01){ //If the random number hits between this, create a heal tile
                    x[i][j] = TileType.HEAL;
                }
                else if (tileRand <= WALL_CHANCE){  //0.05 as stated above in previous code for walls
                    x[i][j] = TileType.WALL;     //Creates a wall tile in that part of the array
                }
                else{
                    x[i][j] = TileType.FLOOR; //Creates a floor tile in that part of the array
                }
            }
        }
        //This chooses and spawns the stair tyle in a random location
        boolean loop = false;
        do{
            int rand = (int) (Math.random() * 24); 
            int rand2 = (int) (Math.random() * 17); //Random numbers to choose in the array
            if(x[rand][rand2] == TileType.FLOOR){
                x[rand][rand2] = TileType.STAIRS; //Inputting the stair tile into a randomly chosen part
                loop = true;
            }
        }while(loop == false);
        return x;    //return the 2D array
    }
    
    /**
     * Generates spawn points for the player and monsters. The method processes
     * the tiles array and finds tiles that are suitable for spawning, i.e.
     * tiles that are not walls or stairs. Suitable tiles should be added
     * to the ArrayList that will contain Point objects - Points are a
     * simple kind of object that contain an X and a Y co-ordinate stored using
     * the int primitive type and are part of the Java language (search for the
     * Point API documentation and examples of their use)
     * @return An ArrayList containing Point objects representing suitable X and
     * Y co-ordinates in the current level that the player or monsters can be
     * spawned in
     */
    private ArrayList<Point> getSpawns() {
        ArrayList<Point> s = new ArrayList<Point>();
        for (int i = 0; i < 24; i++){
            for (int j = 0; j < 17; j++){ //nested for loop to itterate through every part of the array
                if (tiles[i][j] == TileType.FLOOR){
                    s.add(new Point(i, j)); //If a suitable TileType has been found, add that co-ordinate to the ArrayList
                }
            }
        }
        return s; //Returns the ArrayList of Points 
    }

    /**
     * Spawns monsters in suitable locations in the current level. The method
     * uses the spawns ArrayList to pick suitable positions to add monsters,
     * removing these positions from the spawns ArrayList as they are used
     * (using the remove() method) to avoid multiple monsters spawning in the
     * same location. The method creates monsters by instantiating the Entity
     * class, setting health, and setting the X and Y position for the monster
     * using the X and Y values in the Point object removed from the spawns ArrayList.
     * @return A array of Entity objects representing the monsters for the current
     * level of the dungeon
     */
    private Entity[] spawnMonsters() {
        //Create 1 slow insta kill monster? Use a boolean to see if its allowed to move or not, also -1 to upperbound and add slow monster last.
        //Also need to create an image for this dragon and give it a new entity type maybe?
        int upperBound = 5, lowerBound = 0; //The variable for controlling how big the amount of monsters can be
        for(int i = 0; i < depth; i++){ //The loop to make the game harder everytime the player goes down a level
            if(upperBound < 39){ //Checking to see if the upperBound is not already at max
                if(upperBound > 34){ //Checking to see if adding 5 would exceed the maximum amount of monsters allowed - 1
                    upperBound = 39; //Instead putting the amount of monsters to maximum - 1, to allow for one additional monster
                }
                else{
                    upperBound = upperBound + 5;
                }
            }
            if(lowerBound < 39){ //Checking to see if the lowerBound is not already at max
                if(lowerBound > 37){ //Checking to see if adding 2 would exceed the maximum amount of monsters allowed - 1
                    lowerBound = 39; //Instead putting the amount of monsters to maximum - 1, to allow for one additional monster
                }
                else{
                    lowerBound = lowerBound + 2;
                }
            }
        }
        int noOfMons = (int) (Math.random() * ((upperBound - lowerBound) + 1)) + lowerBound; //upperbound and lowerBound to limit how many monsters spawn
        int x, y;
        boolean loop = true;
        Entity[] monArray = new Entity[39]; //Array of the Entity
        for (int i = 0; i < noOfMons; i++){
            int z = spawns.size(); //z = number of elements in the ArrayList
            z = (int) (Math.random() * z); //Random number with upperbound of z
            x = spawns.get(z).x;
            y = spawns.get(z).y; //grabs the co-ordinates of a random element
            Entity mon = new Entity(100, x, y, EntityType.MONSTER); //Creates an entity of mon (standing for monster)
            monArray[i] = mon; //Puts that mon entity into the array
            spawns.remove(z); //removes the point as it is not safe to spawn anything else there that uses the ArrayList
        }
        
        return monArray;
    }

    /**
     * Spawns a player entity in the game. The method uses the spawns ArrayList
     * to select a suitable location to spawn the player and removes the Point
     * from the spawns ArrayList. The method instantiates the Entity class and
     * assigns values for the health, position and type of Entity.
     * @return An Entity object representing the player in the game
     */
    private Entity spawnPlayer() {
        int z = spawns.size(); //z = number of elements in the ArrayList
        z = (int) (Math.random() * z); //Random number with upperbound of z
        int x = spawns.get(z).x;
        int y = spawns.get(z).y; //grabs the co-ordinates of a random element
        Entity p1 = new Entity(100, x, y, EntityType.PLAYER); //Creates the entity of p1 (standing for player1)
        spawns.remove(z); //removes the point as it is not safe to spawn anything else there that uses the ArrayList
        return p1;    //returns the Entity
    }

    /**
     * Handles the movement of the player when attempting to move left in the
     * game. This method is called by the DungeonInputHandler class when the
     * user has pressed the left arrow key on the keyboard. The method checks
     * whether the tile to the left of the player is empty for movement and if
     * it is updates the player object's X and Y locations with the new position.
     * If the tile to the left of the player is not empty the method will not
     * update the player position, but may make other changes to the game, such
     * as damaging a monster in the tile to the left, or breaking a wall etc.
     */
    public void movePlayerLeft() {
        if(player.getX() - 1 >= 0){ //This checks to see if the player is attempting to go past the limits of the game
            if(tiles[player.getX() - 1][player.getY()] != TileType.WALL){ //This checks if the player is not attempting to move through a wall
                player.setPosition(player.getX() - 1, player.getY()); //moves the player to the requested location
                for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
                    if(monsters[i] != null){
                        if(monsters[i].getX() == player.getX() && monsters[i].getY() == player.getY()){ //Checking to see if the player has made contact with the monster
                            hitMonster(monsters[i]); //run the method to hit a monster
                            player.setPosition(player.getX() + 1, player.getY()); //moves the player back to its original square to make sure that hes always where he visually is.
                        }
                    }
                }
            }
        }
        if(tiles[player.getX()][player.getY()] == TileType.HEAL){ //checking to see if the player is on the Heal tile
            player.changeHealth(5); //increasing the health of the player by 5, if the players health would exceed 100, the code automatically changes the health to 100
        }
    }

    /**
     * Handles the movement of the player when attempting to move right in the
     * game. This method is called by the DungeonInputHandler class when the
     * user has pressed the right arrow key on the keyboard. The method checks
     * whether the tile to the right of the player is empty for movement and if
     * it is updates the player object's X and Y locations with the new position.
     * If the tile to the right of the player is not empty the method will not
     * update the player position, but may make other changes to the game, such
     * as damaging a monster in the tile to the right, or breaking a wall etc.
     */
    public void movePlayerRight() {
        if(player.getX() + 1 <= 23){ //This checks to see if the player is not attempting to go past the limits of the game
            if(tiles[player.getX() + 1][player.getY()] != TileType.WALL){ //This checks if the player is not attempting to move through a wall
                player.setPosition(player.getX() + 1, player.getY()); //moves the player to the requested location
                for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
                    if(monsters[i] != null){
                        if(monsters[i].getX() == player.getX() && monsters[i].getY() == player.getY()){ //Checking to see if the player has made contact with the monster
                            hitMonster(monsters[i]); //run the method to hit a monster
                            player.setPosition(player.getX() - 1, player.getY()); //moves the player back to its original square to make sure that hes always where he visually is
                        }
                    }
                }
            }
        }
        if(tiles[player.getX()][player.getY()] == TileType.HEAL){ //checking to see if the player is on the Heal tile
            player.changeHealth(5); //increasing the health of the player by 5, if the players health would exceed 100, the code automatically changes the health to 100
        }
    }

    /**
     * Handles the movement of the player when attempting to move up in the
     * game. This method is called by the DungeonInputHandler class when the
     * user has pressed the up arrow key on the keyboard. The method checks
     * whether the tile above the player is empty for movement and if
     * it is updates the player object's X and Y locations with the new position.
     * If the tile above the player is not empty the method will not
     * update the player position, but may make other changes to the game, such
     * as damaging a monster in the tile above the player, or breaking a wall etc.
     */
    public void movePlayerUp() {
        if(player.getY() - 1 >= 0){ //This checks to see if the player is not attempting to go past the limits of the game
            if(tiles[player.getX()][player.getY() - 1] != TileType.WALL){ //This checks if the player is not attempting to move through a wall
                player.setPosition(player.getX(), player.getY() - 1); //moves the player to the requested location
                for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
                    if(monsters[i] != null){
                        if(monsters[i].getX() == player.getX() && monsters[i].getY() == player.getY()){ //Checking to see if the player has made contact with the monster
                            hitMonster(monsters[i]); //run the method to hit a monster
                            player.setPosition(player.getX(), player.getY() + 1); //moves the player back to its original square to make sure that hes always where he visually is
                        }
                    }
                }
            }
        }
        if(tiles[player.getX()][player.getY()] == TileType.HEAL){ //checking to see if the player is on the Heal tile
            player.changeHealth(5); //increasing the health of the player by 5, if the players health would exceed 100, the code automatically changes the health to 100
        }
    }

    /**
     * Handles the movement of the player when attempting to move right in the
     * game. This method is called by the DungeonInputHandler class when the
     * user has pressed the down arrow key on the keyboard. The method checks
     * whether the tile below the player is empty for movement and if
     * it is updates the player object's X and Y locations with the new position.
     * If the tile below the player is not empty the method will not
     * update the player position, but may make other changes to the game, such
     * as damaging a monster in the tile below the player, or breaking a wall etc.
     */
    public void movePlayerDown() {
        if(player.getY() + 1 <= 16){ //This checks to see if the player is not attempting to go past the limits of the game
            if(tiles[player.getX()][player.getY() + 1] != TileType.WALL){ //This checks if the player is not attempting to move through a wall
                player.setPosition(player.getX(), player.getY() + 1); //moves the player to the requested location
                for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
                    if(monsters[i] != null){
                        if(monsters[i].getX() == player.getX() && monsters[i].getY() == player.getY()){ //Checking to see if the player has made contact with the monster
                            hitMonster(monsters[i]); //run the method to hit a monster
                            player.setPosition(player.getX(), player.getY() - 1); //moves the player back to its original square to make sure that hes always where he visually is
                        }
                    }
                }
            }
        }
        if(tiles[player.getX()][player.getY()] == TileType.HEAL){ //checking to see if the player is on the Heal tile
            player.changeHealth(5); //increasing the health of the player by 5, if the players health would exceed 100, the code automatically changes the health to 100
        }
    }
    
    /**
     * Handles the movement of the player when attempting waiting on the same 
     * tile in the game. This method is called by the DungeonInputHandler class 
     * when the user has pressed any key on the keyboard, other than the arrow
     * keys. The method checks if the player is already on a tile that affects 
     * the player as well as allowing any nearby monster to move and attack the 
     * player.
     */
    public void movePlayerWait(){
        if(tiles[player.getX()][player.getY()] == TileType.HEAL){ //checking to see if the player is on the Heal tile
            player.changeHealth(5); //increasing the health of the player by 5, if the players health would exceed 100, the code automatically changes the health to 100
        }
    }

    /**
     * Reduces a monster's health in response to the player attempting to move
     * into the same square as the monster (attacking the monster).
     * @param m The Entity which is the monster that the player is attacking
     */
    private void hitMonster(Entity m) {
        m.changeHealth(-50); //changing the players health by -50 if they are hit
    }

    /**
     * Moves all monsters on the current level. The method processes all non-null
     * elements in the monsters array and calls the moveMonster method for each one.
     */
    private void moveMonsters() {
        for(int i = 0; i < monsters.length; i++){
            if(monsters[i] != null){
                moveMonster(monsters[i]);
            }
        }
    }
    /**
     * Moves a specific monster in the game. The method updates the X and Y
     * attributes of the monster Entity to reflect its new position.
     * @param m The Entity (monster) that needs to be moved
     */
    private void moveMonster(Entity m) {
        int increment = 3;
        int xpos = m.getX() - player.getX();
        xpos = Math.abs(xpos);
        int ypos = m.getY() - player.getY();
        ypos = Math.abs(ypos);
        for(int i = 0; i < depth; i++){
            increment++;
        }
        if((xpos == 0 && ypos == 1) || (xpos == 1 && ypos == 0)){
            hitPlayer();
        }
        else if((xpos < increment) && (ypos < increment)){ //Moves the monsters only if the player is nearby
            if((xpos - ypos) < 0){ //This is checking which co-ordinate from the monster is further away, then proceeds to move on that co-ordinate
                moveY(m); //runs the moveY method inputting the current monster into the method
            }
            else{
                moveX(m); //runs the moveX method inputting the current monster into the method
            }
        }
    }
    //They still stack!
    private void moveX(Entity m){
        if((m.getX() - player.getX()) < 0){ //check to move right
            if (tiles[m.getX() + 1][m.getY()] != TileType.WALL){ //Checks for walls
                if(m.getX() + 1 <= 23){ //Checks to see if monster is on the edge of the map
                    m.setPosition(m.getX() + 1, m.getY()); //Moves monster right
                    for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
                        if(monsters[i] != null && monsters[i] != m){
                            if(monsters[i].getX() == m.getX() && monsters[i].getY() == m.getY()){ //Checking to see if the current monster has made contact with another monster
                                m.setPosition(m.getX() - 1, m.getY()); //moves the monster back to stop monsters from stacking
                            }
                        }
                    }
                }
                else{
                    //If they hit the edge of the screen
                    if (tiles[m.getX()][m.getY() + 1] != TileType.WALL || tiles[m.getX()][m.getY() - 1] != TileType.WALL){ //Checks to see if theres a possible movement option on the Y axis
                        moveY(m); //This is to move on the Y co-ordinate since the X co-ordinate would either walk away from the target or get stuck there
                    }
                }
                
            }
            else{
                if (tiles[m.getX()][m.getY() + 1] != TileType.WALL || tiles[m.getX()][m.getY() - 1] != TileType.WALL){ //Checks to see if theres a possible movement option on the Y axis
                    moveY(m); //This is to move on the Y co-ordinate since the X co-ordinate would either walk away from the target or get stuck there
                }
            }
        }
        else{
            //check to move left
            if (tiles[m.getX() - 1][m.getY()] != TileType.WALL){ //Checks for walls
                if(m.getX() - 1 >= 0){ //Checks to see if monster is on the edge of the map
                    m.setPosition(m.getX() - 1, m.getY()); //Moves monster left
                    for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
                        if(monsters[i] != null && monsters[i] != m){
                            if(monsters[i].getX() == m.getX() && monsters[i].getY() == m.getY()){ //Checking to see if the current monster has made contact with another monster
                                m.setPosition(m.getX() - 1, m.getY()); //moves the monster back to stop monsters from stacking
                            }
                        }
                    }
                }
                
                else{
                    //If they hit the edge of the screen
                    if (tiles[m.getX()][m.getY() + 1] != TileType.WALL || tiles[m.getX()][m.getY() - 1] != TileType.WALL){ //Checks to see if theres a possible movement option on the Y axis
                        moveY(m); //This is to move on the Y co-ordinate since the X co-ordinate would either walk away from the target or get stuck there   
                    }
                }
            }
            else{
                if (tiles[m.getX()][m.getY() + 1] != TileType.WALL || tiles[m.getX()][m.getY() - 1] != TileType.WALL){ //Checks to see if theres a possible movement option on the Y axis
                        moveY(m); //This is to move on the Y co-ordinate since the X co-ordinate would either walk away from the target or get stuck there
                }
            }
        }
    }
    
    private void moveY(Entity m){
        if((m.getY() - player.getY()) > 0){ //Checks to move up
            if(tiles[m.getX()][m.getY() - 1] != TileType.WALL){ //Checks for walls
                if(m.getY() - 1 >= 0){ //Checks to see if monster is on the edge of the map
                    m.setPosition(m.getX(), m.getY() - 1); //Moves monster up
                    for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
                        if(monsters[i] != null && monsters[i] != m){
                            if(monsters[i].getX() == m.getX() && monsters[i].getY() == m.getY()){ //Checking to see if the current monster has made contact with another monster
                                m.setPosition(m.getX(), m.getY() + 1); //moves the monster back to stop monsters from stacking
                            }
                        }
                    }
                }
                //If they hit the edge of the screen
                else{
                    if (tiles[m.getX() + 1][m.getY()] != TileType.WALL || tiles[m.getX() - 1][m.getY()] != TileType.WALL){ //Checks to see if theres a possible movement option on the X axis
                        moveX(m); //This is to move on the X co-ordinate since the Y co-ordinate would either walk away from the target or get stuck there
                    }
                }
                
            }
            else{
                if (tiles[m.getX() + 1][m.getY()] != TileType.WALL || tiles[m.getX() - 1][m.getY()] != TileType.WALL){ //Checks to see if theres a possible movement option 
                    moveX(m); //This is to move on the X co-ordinate since the Y co-ordinate would either walk away from the target or get stuck there
                }
            }
        }
        else{
            //Check to move down
            if(tiles[m.getX()][m.getY() + 1] != TileType.WALL){ //Checks for walls
                if(player.getY() + 1 <= 16){ //Checks to see if monster is on the edge of the map
                     m.setPosition(m.getX(), m.getY() + 1); //Moves monster down
                     for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
                        if(monsters[i] != null && monsters[i] != m){
                            if(monsters[i].getX() == m.getX() && monsters[i].getY() == m.getY()){ //Checking to see if the current monster has made contact with another monster
                                m.setPosition(m.getX(), m.getY() - 1); //moves the monster back to stop monsters from stacking
                            }
                        }
                    }
                }
                //If they hit the edge of the screen
                else{
                    if (tiles[m.getX() + 1][m.getY()] != TileType.WALL || tiles[m.getX() - 1][m.getY()] != TileType.WALL){ //Checks to see if theres a possible movement option on the X axis
                        moveX(m); //This is to move on the X co-ordinate since the Y co-ordinate would either walk away from the target or get stuck there
                    }
                }
            }
            else{
                if (tiles[m.getX() + 1][m.getY()] != TileType.WALL || tiles[m.getX() - 1][m.getY()] != TileType.WALL){ //Checks to see if theres a possible movement option on the X axis
                    if (tiles[m.getX() + 1][m.getY()] != TileType.WALL || tiles[m.getX() - 1][m.getY()] != TileType.WALL){ //Checks to see if theres a possible movement option 
                        moveX(m); //This is to move on the X co-ordinate since the Y co-ordinate would either walk away from the target or get stuck there
                    }
                }
            }
        }
    }

    /**
     * Reduces the health of the player when hit by a monster - a monster next
     * to the player can attack it instead of moving and should call this method
     * to reduce the player's health
     */
    private void hitPlayer() {
        int Damage = 10;
        for(int i = 0; i < depth; i++){ //To make the game more difficult for how many stairs you've taken
            Damage = Damage + 2; //Increased by increments of 2
        }
        int monDamage = (int) (Math.random() * Damage); //Randomly choosing a number between 0 and Damage
        monDamage = -monDamage; //Making that number a negative
        player.changeHealth(monDamage); //decreasing the players health based on monDamage
    }

    /**
     * Processes the monsters array to find any Entity in the array with 0 or
     * less health. Any Entity in the array with 0 or less health should be
     * set to null; when drawing or moving monsters the null elements in the
     * monsters array are skipped.
     */
    private void cleanDeadMonsters() {
        for(int i = 0; i < monsters.length; i++){ //Re-running the loop to go through all monsters
            if(monsters[i] != null){ //For each non null monster
                if(monsters[i].getHealth() <= 0){ //Check health
                    monsters[i] = null; //If health < 0, make monster null
                }
            }
        }
    }

    /**
     * Called in response to the player moving into a Stair tile in the game.
     * The method increases the dungeon depth, generates a new level by calling
     * the generateLevel method, fills the spawns ArrayList with suitable spawn
     * locations and spawns monsters. Finally it places the player in the new
     * level by calling the placePlayer() method. Note that a new player object
     * should not be created here unless the health of the player should be reset.
     */
    private void descendLevel() {
        //The next step, use for loops in everything you want to increase in difficulty e.g: for(int i = 0; i < depth; i++)
        depth++; //Incrementing the Depth counter
        //int x = player.getX();
        //int y = player.getY();
        startGame();
        //player.setPosition(x, y); This does not work, because of "spawns" 
        
    }

    /**
     * Places the player in a dungeon level by choosing a spawn location from the
     * spawns ArrayList, removing the spawn position as it is used. The method sets
     * the players position in the level by calling its setPosition method with the
     * x and y values of the Point taken from the spawns ArrayList.
     */
    private void placePlayer() {
        
    }

    /**
     * Performs a single turn of the game when the user presses a key on the
     * keyboard. The method cleans dead monsters, moves any monsters still alive
     * and then checks if the player is dead, exiting the game or resetting it
     * after an appropriate output to the user is given. It checks if the player
     * moved into a stair tile and calls the descendLevel method if it does.
     * Finally it requests the GUI to redraw the game level by passing it the
     * tiles, player and monsters for the current level.
     */
    public void doTurn() {
        cleanDeadMonsters();
        moveMonsters();
        if (player != null) {       //checks a player object exists
            if (player.getHealth() < 1) {
                System.exit(0);     //exits the game when player is dead
            }
            if (tiles[player.getX()][player.getY()] == TileType.STAIRS) {
                descendLevel();     //moves to next level if the player is on Stairs
            }
        }
        gui.updateDisplay(tiles, player, monsters);     //updates GUI
    }

    /**
     * Starts a game. This method generates a level, finds spawn positions in
     * the level, spawns monsters and the player and then requests the GUI to
     * update the level on screen using the information on tiles, player and
     * monsters.
     */
    public void startGame() {
        tiles = generateLevel();
        spawns = getSpawns();
        monsters = spawnMonsters();
        player = spawnPlayer();
        gui.updateDisplay(tiles, player, monsters);
    }
}
