abstract class Character implements Entity {
    int health;
    int lvl;
    float speed;

    public abstract void say();
    
    public void move() {
        System.out.println("hi!");
    }

    protected Character(int health, int lvl, float speed) {
        this.health = health;
        this.lvl = lvl;
        this.speed = speed;
    }

    int generateRandomHealth() {
        return (int) (Math.random() * 100);
    }

    int generateRandomLevel() {
        return (int) (Math.random() * 10);
    }

    float generateRandomSpeed() {
        return (float) (Math.random() * 50);
    }

    protected Character () {
        this.health = generateRandomHealth();
        this.lvl = generateRandomLevel();
        this.speed = generateRandomSpeed();
    }

    static Character fromKeyboard() {
        return null;
    }

    abstract void saveToFile(String filename);
}