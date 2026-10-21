import java.util.ArrayList;

public class Main {
    public static void main(String[] args) {
        System.out.println("\n");
        
        Rogue rogue = new Rogue(56, 3, 12.5f, 8);
        Wizard wizard = new Wizard(76, 5, 20.0f, 10);
        Barbarian barbarian = Barbarian.fromKeyboard();

        System.out.println("\n");

        Druid druid = new Druid();

        ArrayList<Character> characters = new ArrayList<>();
        characters.add(rogue);
        characters.add(wizard);
        characters.add(barbarian);
        characters.add(druid);

        ArrayList<Support> supportCharacters = new ArrayList<>();
        supportCharacters.add(rogue);
        supportCharacters.add(druid);

        ArrayList<Battler> battlerCharacters = new ArrayList<>();
        battlerCharacters.add(wizard);
        battlerCharacters.add(barbarian);

        System.out.println("\n");

        for (Character character : characters) {
            System.out.println(
                "Character: " + character.getClass().getSimpleName() +
                ", Health: " + character.health +
                ", Level: " + character.lvl +
                ", Speed: " + character.speed
            );
            
            character.say();
            character.move();

            if (character instanceof Druid) {
                ((Druid) character).summon();
            } else if (character instanceof Rogue) {
                ((Rogue) character).sneak();
            } else if (character instanceof Wizard) {
                ((Wizard) character).cast();
            } else if (character instanceof Barbarian) {
                ((Barbarian) character).fury();
            }
        }

        System.out.println("\n");

        for (Support support : supportCharacters) {
            support.help((Character) support);
            support.heal((Character) support);
            support.protect((Character) support);
        }

        System.out.println("\n");

        for (Battler battler : battlerCharacters) {
            battler.attack((Character) battler);
            battler.shoot((Character) battler);
        }

        System.out.println("\n");
        
        for (Character character : characters) {
            System.out.println(
                "Character: " + character.getClass().getSimpleName() +
                ", Health: " + character.health +
                ", Level: " + character.lvl +
                ", Speed: " + character.speed
            );
         }

        System.out.println("\n");

        int w = druid.wisdom;
        int a = rogue.agility;
        int s = wizard.spells;
        int st = barbarian.strength;

        System.out.println("Druid wisdom: " + w);
        System.out.println("Rogue agility: " + a);
        System.out.println("Wizard spells: " + s);
        System.out.println("Barbarian strength: " + st);

        System.out.println("\n");

        for (Character character : characters) {
            character.saveToFile(character.getClass().getSimpleName() + ".txt");
        }
    }
}