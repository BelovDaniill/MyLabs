public final class Druid extends Character implements Support {
    
    // статик
    static int count = 0;

    // уникальный
    int wisdom;

    // уникальный
    void summon() {
        System.out.println("Druid is summoning a creature");
    }

    // переопределение метода суперкласса
    public void move() {
        System.out.println("Druid is moving");
    }

    // метод суперкласса
    public void say() {
        System.out.println("Druid says: I am a nature-loving character!");
    }

    // метод интерфейса
    public Character heal(Character character) {
        character.health += 10;
        System.out.println("Druid is healing " + character.getClass().getSimpleName());
        return character;
    }

    // метод интерфейса
    public Character help(Character character) {
        System.out.println("Druid is helping " + character.getClass().getSimpleName());
        return character;
    }
 
    // метод интерфейса
    public Character protect(Character character) {
        System.out.println("Druid is protecting " + character.getClass().getSimpleName());
        return character;
    }


    public Druid(int health, int lvl, float speed, int wisdom) {
        super(health, lvl, speed);
        this.wisdom = wisdom;
        count++;
    }

    public Druid() {
        super();
        this.wisdom = (int) (Math.random() * 10);
        System.out.println("Generating random attributes for Druid...");
        count++;
    }

    // метод суперкласса
    public static Druid fromKeyboard() {
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
        System.out.print("Enter wisdom (1-10): ");
        int wisdom = scanner.nextInt();
        if (wisdom < 1 || wisdom > 10) {
            System.out.println("Invalid wisdom value. Setting to default (5).");
            wisdom = 5;
        }
        return new Druid(health, lvl, speed, wisdom);
    }

    void saveToFile(String filename) {
        try (java.io.FileWriter writer = new java.io.FileWriter(filename)) {
            writer.write("Druid\n");
            writer.write("Health: " + health + "\n");
            writer.write("Level: " + lvl + "\n");
            writer.write("Speed: " + speed + "\n");
            writer.write("Wisdom: " + wisdom + "\n");
        } catch (java.io.IOException e) {
            System.out.println("An error occurred while saving to file: " + e.getMessage());
        }
    }

}