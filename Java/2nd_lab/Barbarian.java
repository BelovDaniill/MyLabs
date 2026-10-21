public class Barbarian extends Character implements Battler{

    // статик
    static int count = 0;

    // уникальный
    final int strength;

    // уникальный
    final void fury() {
        System.out.println("Barbarian is in a fury");
    }

    // переопределение метода суперкласса
    public void move() {
        System.out.println("Barbarian is moving");
    }

    // метод суперкласса
    public void say() {
        System.out.println("Barbarian says: I am a strong character!");
    }

    // метод интерфейса
    public Character attack(Character character) {
        character.health -= 10;
        System.out.println("Barbarian is attacking " + character.getClass().getSimpleName());
        return character;
    }

    // метод интерфейса
    public Character shoot(Character character) {
        character.health -= 5;
        System.out.println("Barbarian is shooting at " + character.getClass().getSimpleName());
        return character;
    }


    public Barbarian(int health, int lvl, float speed, int strength) {
        super(health, lvl, speed);
        this.strength = strength;
        count++;
    }
    
    public Barbarian() {
        super();
        this.strength = (int) (Math.random() * 10);
        System.out.println("Generating random attributes for Barbarian...");
        count++;
    }

    // метод суперкласса
    public static Barbarian fromKeyboard() {
        java.util.Scanner scanner = new java.util.Scanner(System.in);
        System.out.print("Enter health (1-100): ");
        int health = scanner.nextInt();
        if (health < 1 || health > 100) {
            System.out.println("Invalid health value. Setting to default (50).");
            health = 50;
        }
        System.out.print("Enter level (1-20): ");
        int lvl = scanner.nextInt();
        if (lvl < 1 || lvl > 20) {
            System.out.println("Invalid level value. Setting to default (1).");
            lvl = 1;
        }
        System.out.print("Enter speed (1.0-40.0): ");
        float speed = scanner.nextFloat();
        if (speed < 1.0 || speed > 40.0) {
            System.out.println("Invalid speed value. Setting to default (10.0).");
            speed = 10.0f;
        }
        System.out.print("Enter strength (1-10): ");
        int strength = scanner.nextInt();
        if (strength < 1 || strength > 10) {
            System.out.println("Invalid strength value. Setting to default (5).");
            strength = 5;
        }
        return new Barbarian(health, lvl, speed, strength);
    }

    void saveToFile(String filename) {
        try (java.io.FileWriter writer = new java.io.FileWriter(filename)) {
            writer.write("Barbarian\n");
            writer.write("Health: " + health + "\n");
            writer.write("Level: " + lvl + "\n");
            writer.write("Speed: " + speed + "\n");
            writer.write("Strength: " + strength + "\n");
        } catch (java.io.IOException e) {
            System.out.println("An error occurred while saving to file: " + e.getMessage());
        }
    }
}