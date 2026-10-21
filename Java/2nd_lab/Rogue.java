public final class Rogue extends Character implements Support{
    
    // статик
    static int count = 0;

    // уникальный
    int agility;

    // уникальный
    void sneak() {
        System.out.println("Rogue is sneaking");
    }
    
    // переопределение метода суперкласса
    public void move() {
        System.out.println("Rogue is moving");
    }

    // метод суперкласса
    public void say() {
        System.out.println("Rogue says: I am a stealthy character!");
    }

    // метод интерфейса
    public Character heal(Character character) {
        character.health += 10;
        System.out.println("Rogue is healing " + character.getClass().getSimpleName());
        return character;
    }

    // метод интерфейса
    public Character help(Character character) {
        System.out.println("Rogue is helping " + character.getClass().getSimpleName());
        return character;
    }
 
    // метод интерфейса
    public Character protect(Character character) {
        System.out.println("Rogue is protecting " + character.getClass().getSimpleName());
        return character;
    }


    public Rogue(int health, int lvl, float speed, int agility) {
        super(health, lvl, speed);
        this.agility = agility;
        count++;
    }

    public Rogue() {
        super();
        this.agility = (int) (Math.random() * 10);
        System.out.println("Generating random attributes for Rogue...");
        count++;
    }

    // метод суперкласса
    public static Rogue fromKeyboard() {
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
        System.out.print("Enter agility (1-10): ");
        int agility = scanner.nextInt();
        if (agility < 1 || agility > 10) {
            System.out.println("Invalid agility value. Setting to default (5).");
            agility = 5;
        }
        return new Rogue(health, lvl, speed, agility);
    }

    void saveToFile(String filename) {
        try (java.io.FileWriter writer = new java.io.FileWriter(filename)) {
            writer.write("Rogue\n");
            writer.write("Health: " + health + "\n");
            writer.write("Level: " + lvl + "\n");
            writer.write("Speed: " + speed + "\n");
            writer.write("Agility: " + agility + "\n");
        } catch (java.io.IOException e) {
            System.out.println("An error occurred while saving to file: " + e.getMessage());
        }
    }
}