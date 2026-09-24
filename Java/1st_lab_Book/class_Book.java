import java.io.*;
import java.util.ArrayList;
import java.util.Random;


/*                  ----------- ТЗ ------------

Создать класс ”Книга” с несколькими полями. 
Обязательные поля:
- дробное поле, хранящее стоимость одного экземпляра копии книги.
- 2 строковых поля, хранящие название книги и имя-фамилию автора
- целочисленное поле, хранящее число копий этой книги
- динамический вектор, хранящий инфу о состоянии потрепанности каждой копии (от 0 до 10)
- статическое целочисленное поле, в котором будет храниться число созданных книг.

Необязательные поля: упитанность книги (число страниц), жанр, цвет обложки, язык и пр.

Описать три типа конструкторов этого класса (с динамическим выделением памяти):
- конструктор по умолчанию (без параметров) 
- несколько разных конструкторов с параметрами
- конструктор копирования

Описать функции доступа и изменения полей класса (методы set()  и get()).
Описать функцию вывода на экран всей инфы о книге, т.е. всех полей класса.
Описать функцию, заполняющую все поля объекта значениями, считанными с клавиатуры.
Описать функцию, заполняющую все поля вызывающего объекта случайными значениями. 
Описать функцию подсчета популярности книги (среднее арифметическое изношенности копий)
Описать функцию подсчета общей стоимости книги (учитывая цену одной копии и число копий)
Описать функцию, сравнивающую две книги (вызывающую и принятую как параметр) по степени 
их популярности, т.е. по среднему арифметическому среди коэффициентов износа всех копий 
книги. Чем выше коэффициент – тем популярнее книга.
Описать статическую функцию, принимающую как параметры две книги, и считающую на сколько 
первая книга дороже второй (учитывая число копий книги).

В функции main() понапечатать книг, используя все описанные конструкторы. После создания 
каждого нового экземпляра класса “Книга”, вывести на экран его поля методом класса. 
Создать динамический вектор книг – «библиотека». Инициализировать его по частям,  
используя разные типы конструкторов. В цикле вывести всю инфу о каждой книге из 
библиотеки. Сравнить несколько пар книг по популярности и по стоимости. Подсчитать и 
распечатать общую стоимость всех созданных книг. Найти среди всех книг самую популярную, 
и напечатать название «чемпионки». Последней строкой вывести число созданных книг, 
используя статическую переменную класса.

На оценку 10. Добавить ко всему вышеперечисленному:
- конструктор, принимающий как параметр имя текстового файла (String), откуда берутся 
данные для полей создаваемого объекта;
- функцию сохранения всех полей класса в текстовый файл, имя которого принимается как 
параметр. 
- в функции main сохранить данные всех созданных объектов в текстовые файлы, именами 
которых являются имена объектов.

*/



class Book{
    private static int numOfBooks = 0;
    private String name = "Unknown";
    private String author = "Unknown";
    private double price = 0.0;                 //<0.0
    private int numOfCopies = 0;
    private int qualityOfCopies[];              //0-10
    private int numOfPages = 0;                 //optional
    private String genre = "Unknown";           //optional
    private String colorOfCover = "Unknown";    //optional
    private String language = "Unknown";        //optional

    // Default constructor
    public Book() {
        this.qualityOfCopies = new int[0];
        numOfBooks++;
    }

    // Main storage for books
    static Book mainStorage[] = new Book[1];

    // Optional parameterized constructor
    public Book(
        String name,
        String author,
        double price,
        int numOfCopies, 
        int qualityOfCopies[])
        {
        this.name = name;
        this.author = author;
        this.price = price;
        this.numOfCopies = numOfCopies;
        this.qualityOfCopies = qualityOfCopies;
        numOfPages = 0;                         //optional
        genre = "Unknown";                      //optional
        colorOfCover = "Unknown";               //optional
        language = "Unknown";                   //optional

        numOfBooks++;
        }

    // Full parameterized constructor
    public Book(
        String name,
        String author,
        double price,
        int numOfCopies, 
        int qualityOfCopies[],
        int numOfPages,
        String genre,
        String colorOfCover,
        String language)
        {
        this.name = name;
        this.author = author;
        this.price = price;
        this.numOfCopies = numOfCopies;
        this.qualityOfCopies = qualityOfCopies;
        this.numOfPages = numOfPages;
        this.genre = genre;
        this.colorOfCover = colorOfCover;
        this.language = language;

        numOfBooks++;
        }

    // Copy constructor
    public Book(Book otherBook){
        this.name = otherBook.name;
        this.author = otherBook.author;
        this.price = otherBook.price;
        this.numOfCopies = otherBook.numOfCopies;

        if (otherBook.qualityOfCopies != null) {
            this.qualityOfCopies = new int[otherBook.qualityOfCopies.length];
            for(int i = 0; i < otherBook.qualityOfCopies.length; i++) {
                this.qualityOfCopies[i] = otherBook.qualityOfCopies[i];
            }
        } else {
            this.qualityOfCopies = null;
        }
        this.numOfPages = otherBook.numOfPages;
        this.genre = otherBook.genre;
        this.colorOfCover = otherBook.colorOfCover;
        this.language = otherBook.language;

        numOfBooks++;
        }

    // Unnormal reading stuff
    static String inString() {
	String str = " ";
	BufferedReader box = new BufferedReader(new InputStreamReader(System.in));
	try{
		str = box.readLine();
	}
	catch (IOException e){};
	return str;
    }
    static int inInt() {
        while (true) {
            try {
                return (Integer.valueOf(inString()).intValue());
            } catch (NumberFormatException e) {
                System.out.println("Invalid input. Please enter an integer.");
            }
        }
    }
    static double inDouble() {
        while (true) {
            try {
                return (Double.valueOf(inString()).doubleValue());
            } catch (NumberFormatException e) {
                System.out.println("Invalid input. Please enter a double.");
            }
        }
    }
    
    

    ///////////////////////////// MAIN /////////////////////////////

    public static void main() {
        mainStorage[0] = new Book();

        // Main menu
        while(true) {
            System.out.println("Choose an option:");
            System.out.println("1. Add a book");
            System.out.println("2. Display book information");
            System.out.println("3. Display total number of books");
            System.out.println("4. Compare 2 books by popularity");
            System.out.println("5. Compare 2 books by cost");
            System.out.println("6. Ignore the fact that I did an interactive menu and run a script");
            System.out.println("7. Save my books");
            System.out.println("8. Download books");
            System.out.println("0. Exit");

        // menu switch
        switch (inString()) {
            // add-a-book case
            case "1":
                mainStorage = fun_for_case_1_in_menu(mainStorage);
                break;
            
            // display-book-information case
            case "2":
                fun_for_case_2_in_menu(mainStorage);
                break;

            // display-total-number-of-books case
            case "3":
                fun_for_case_3_in_menu();
                break;

            // compare-2-books-by-popularity case
            case "4":
                fun_for_case_4_in_menu(mainStorage);
                break;

            // compare-2-books-by-cost case
            case "5":
                fun_for_case_5_in_menu(mainStorage);
                break;

            case "6":
                fun_for_case_6_in_menu();
                System.exit(0);
                break;

            case "7":
                fun_for_case_7_in_menu();
                break;

            case "8":
                mainStorage = fun_for_case_8_in_menu(mainStorage);
                break;

            // exit case
            case "0":
                System.exit(0);
                break;

            // invalid option case
            default:
                System.out.println("Invalid option. Please try again.");
                break;
            }
        }
    }

    // function for case 6 in the main menu
    public static void fun_for_case_6_in_menu(){
        System.out.println("WARNING! This is a static script, if you'll continue you'll lose a chase to tast program flexibility. Do you wanna continue?");
        System.out.println("Y/N");
        
        switch (inString()) {
            case "Y" -> continue_lab_function();
            case "N" -> System.out.println("Function will cancel.");
            default -> System.out.println("Invalid answer!");
        }    
    }

    // continue case 6
    public static void continue_lab_function(){

        ArrayList<Book> storage6_biblioteka = new ArrayList<>();
        
        // creation
        Book noname = new Book();
        Book optional = new Book("Memoirs", "James Bold", 23.0, 3, new int[]{1, 2, 2});
        Book full = new Book("Diary", "lil sis", 1.0, 1, new int[]{9}, 6, "Drama", "Red", "Cantonese");
        Book copy = new Book(optional);
        storage6_biblioteka.add(noname);
        storage6_biblioteka.add(optional);
        storage6_biblioteka.add(full);
        storage6_biblioteka.add(copy);
        copy.randomaize_the_object();

        // description
        System.out.println("--------------");
        System.out.println("All books in array:");
        System.out.println("--------------");
        for(int i = 0; i < storage6_biblioteka.size(); i++){
            System.out.println("Book number " + (i+1) + " :");
            storage6_biblioteka.get(i).book_description();
            System.out.println("--------------");
        }

        // total cost
        System.out.println("--------------");
        System.out.println("Calculating cost ...");
        System.out.println("--------------");
        double total = 0;
        for(int i = 0; i < storage6_biblioteka.size(); i++){
            System.out.println("Cost of book number" + (i+1) + " :");
            total += get_the_cost(storage6_biblioteka.get(i));
            System.out.println("--------------");
        }
        System.out.println("--------------");
        System.out.println("Total cost books in array: " + total);
        System.out.println("--------------");

        // comparation
        System.out.println("--------------");
        System.out.println("Comparing Memois n Diary ...");
        System.out.println("--------------");
        short_cost_compare(optional, copy);
        short_pop_compare(optional, copy);

        // most popular
        System.out.println("--------------");
        System.out.println("Finding most popular ...");
        System.out.println("--------------");
        ArrayList<Book> most_popular = new ArrayList<>();
        most_popular.add(storage6_biblioteka.getFirst());

        for (int i = 1; i < storage6_biblioteka.size(); i++) {
            System.out.println("--------------");
            System.out.println("Popularity of book number " + (i+1) + " = "  + get_the_popularity(storage6_biblioteka.get(i)));
            System.out.println("--------------");
            if (get_the_popularity(storage6_biblioteka.get(i)) == get_the_popularity(most_popular.getFirst())) {
                most_popular.add(storage6_biblioteka.get(i));
            }
            else if (get_the_popularity(storage6_biblioteka.get(i)) > get_the_popularity(most_popular.getFirst())) {
                most_popular.clear();
                most_popular.add(storage6_biblioteka.get(i));
            }
        }

        if (most_popular.size() == 1){
            System.out.println("The most popular book is " + most_popular.getFirst().get_name() + " by " + most_popular.getFirst().get_author());
        }
        else {
            System.out.println("The most popular book are:");
            for (int i = 0; i < most_popular.size(); i++){
                System.out.println(most_popular.get(i).get_name() + " by " + most_popular.get(i).get_author());
            }
        }

        System.out.println("--------------");
        System.out.println("Total books created: " + get_numOfBooks());
        System.out.println("--------------");

    }

    // function for case 1 in the main menu
    public static Book[] fun_for_case_1_in_menu(Book[] mainStorage) {
        System.out.println("Choose an variant:");
        System.out.println("1. Use default constructor");
        System.out.println("2. Use parameterized constructor without optional parameters");
        System.out.println("3. Use parameterized constructor with all parameters");
        System.out.println("4. Use copy constructor");
        System.out.println("5. Surprise me!");
        System.out.println("6. I've changed my mind, go back to the main menu");
                
        // add-a-book switch
        switch (inString()) {
            case "1" -> mainStorage = default_constructor(mainStorage);
            case "2" -> mainStorage = optional_constructor(mainStorage);
            case "3" -> mainStorage = full_constructor(mainStorage);
            case "4" -> mainStorage = copy_constructor(mainStorage);
            case "5" -> mainStorage = random_constructor(mainStorage);
            case "6" -> System.out.println("Returning to the main menu.");
            default -> System.out.println("Invalid option. Please try again.");
        }
        return mainStorage;
    }

    // function for case 2 in the main menu
    public static void fun_for_case_2_in_menu(Book[] mainStorage) {
        if (mainStorage.length == 0) {
            System.out.println("No books available.");
        }
        else {
            System.out.println("Choose a method to display book information:");
            System.out.println("1. Display by index");
            System.out.println("2. Display by name");
            System.out.println("3. Cancel");

            switch (inString()) {
                case "1" -> {
                    System.out.println("Enter the index of the book to display (0 to " + (mainStorage.length - 1) + "):");
                    int index = inInt();
                    Book bookByIndex = find_book_by_index(mainStorage, index);
                    if (bookByIndex != null) {
                        bookByIndex.book_description();
                    }
                }
                case "2" -> {
                    System.out.println("Enter the name of the book to display:");
                    String name = inString();
                    Book bookByName = find_book_by_name(mainStorage, name);
                    if (bookByName != null) {
                        bookByName.book_description();
                    }
                }
                case "3" -> System.out.println("Returning to the main menu.");
                default -> System.out.println("Invalid option. Please try again.");
            }
        }
    }

    // function for case 3 in the main menu
    public static void fun_for_case_3_in_menu() {
        System.out.println("Total number of books: " + get_numOfBooks());
    }

    // function for case 4 in the main menu
    public static void fun_for_case_4_in_menu(Book[] mainStorage) {
        if (mainStorage.length < 2) {
            System.out.println("Not enough books to compare. Please add more books.");
            return;
        }

        System.out.println("Choose the first book to compare:");
        Book firstBook = find_a_book(mainStorage);
        if (firstBook == null) {
            System.out.println("Comparison cancelled.");
            return;
        }

        System.out.println("Choose the second book to compare:");
        Book secondBook = find_a_book(mainStorage);
        if (secondBook == null) {
            System.out.println("Comparison cancelled.");
            return;
        }
        
        if (firstBook == secondBook) {
            System.out.println("You selected the same book twice.");
            return;
        }

        // Compare by popularity
        short_pop_compare(firstBook, secondBook);
        
    }

    public static void short_pop_compare(Book firstBook, Book secondBook){
        double firstBookPopularity = get_the_popularity(firstBook);
        double secondBookPopularity = get_the_popularity(secondBook);

        if (firstBookPopularity > secondBookPopularity) {
            System.out.println(firstBook.get_name() + " is more popular than " + secondBook.get_name() + ".");
        } else if (firstBookPopularity < secondBookPopularity) {
            System.out.println(secondBook.get_name() + " is more popular than " + firstBook.get_name() + ".");
        } else {
            System.out.println(firstBook.get_name() + " and " + secondBook.get_name() + " are equally popular.");
        }
    } 

    // cause of "ТЗ"
    public int compare_popularity_as_in_tz(Book other) {
        double thisPop = get_the_popularity(this);
        double otherPop = get_the_popularity(other);
        return Double.compare(thisPop, otherPop);
    }

    // function for case 5 in the main menu
    public static void fun_for_case_5_in_menu(Book[] mainStorage) {
        if (mainStorage.length < 2) {
            System.out.println("Not enough books to compare. Please add more books.");
            return;
        }

        System.out.println("Choose the first book to compare:");
        Book firstBook = find_a_book(mainStorage);
        if (firstBook == null) {
            System.out.println("Comparison cancelled.");
            return;
        }

        System.out.println("Choose the second book to compare:");
        Book secondBook = find_a_book(mainStorage);
        if (secondBook == null) {
            System.out.println("Comparison cancelled.");
            return;
        }
        
        if (firstBook == secondBook) {
            System.out.println("You selected the same book twice.");
            return;
        }

        // Compare by cost
        short_cost_compare(firstBook, secondBook);

    }

    public static double short_cost_compare(Book firstBook, Book secondBook){
        double firstBookCost = get_the_cost(firstBook);
        double secondBookCost = get_the_cost(secondBook);
        double difference = 0;
        
        if (firstBookCost > secondBookCost) {
            difference = firstBookCost - secondBookCost;
            System.out.println(firstBook.get_name() + " is $" + difference + " more expensive than " + secondBook.get_name() + ".");
        } else if (firstBookCost < secondBookCost) {
            difference = secondBookCost - firstBookCost;
            System.out.println(secondBook.get_name() + " is $" + difference + " more expensive than " + firstBook.get_name() + ".");
        } else {
            System.out.println(firstBook.get_name() + " and " + secondBook.get_name() + " have the same cost.");
        }
        return difference;
    }

    // function for case 7 in the main menu
    // save to file
    public static void fun_for_case_7_in_menu() {
        if (mainStorage == null || mainStorage.length == 0) {
            System.out.println("No books to save.");
            return;
        }
        for (Book b : mainStorage) {
            if (b != null) {
                String rawName = b.get_name();
                if (rawName == null || rawName.trim().isEmpty()) {
                    rawName = "Unnamed_Book";
                }
                // Формируем безопасное имя файла
                String fileName = rawName.replaceAll("[^a-zA-Z0-9_-]", "_") + ".txt";
                b.saveToFile("Java/1st_lab_Book/" + fileName);
            }
        }
    }

    // function for case 8 in the main menu
    // load from file
    public static Book[] fun_for_case_8_in_menu(Book[] arr){
        System.out.println("Choose file to download a book:");
        String name = inString();
        Book loadedBook = new Book("Java/1st_lab_Book/" + name);
        Book array[] = new Book[arr.length + 1];
        for (int i = 0; i < arr.length; i++) {
            array[i] = arr[i];
        }
        array[arr.length] = loadedBook;
        return array;

    }

    // constructors but it is a function that use a real constructor
    
    // default constructor
    public static Book[] default_constructor(Book[] arr) {
        System.out.println("Adding a new book using the default constructor.");

        // work with the array
        Book[] newArray = new Book[arr.length + 1];
        for(int i = 0; i < arr.length; i++) {
            newArray[i] = arr[i];
        }

        // create a new book object and add it to the new array
        newArray[arr.length] = new Book();
        return newArray;
    }
    
    // full constructor
    public static Book[] full_constructor(Book[] arr) {
        System.out.println("Adding a new book using the full parameterized constructor.");

        // read all the parameters from the user
        System.out.println("Enter the book's name:");
        String name = inString();
        
        System.out.println("Enter the book's author:");
        String author = inString();
        
        System.out.println("Enter the book's price:");
        double price = inDouble();
        
        System.out.println("Enter the number of copies:");
        int numOfCopies = inInt();
        
        int[] qualityOfCopies = new int[numOfCopies];
        for(int i = 0; i < numOfCopies;) {
            System.out.println("Enter the quality of copy " + (i + 1) + " (0-10):");
            int curentNumber = inInt();
            if (curentNumber <= 10 && curentNumber >=0) {
                qualityOfCopies[i] = curentNumber;
                i++;
            }
            else {
                System.out.println("Invalid value! (0-10)");
            }
        }
        
        System.out.println("Enter the number of pages:");
        int numOfPages = inInt();
        
        System.out.println("Enter the book's genre:");
        String genre = inString();
        
        System.out.println("Enter the color of the cover:");
        String colorOfCover = inString();
        
        System.out.println("Enter the book's language:");
        String language = inString();

        // work with the array
        Book[] newArray = new Book[arr.length + 1];
        for(int i = 0; i < arr.length; i++) {
            newArray[i] = arr[i];
        }

        // create a new book object and add it to the new array
        newArray[arr.length] = new Book(
            name,
            author,
            price,
            numOfCopies,
            qualityOfCopies,
            numOfPages,
            genre,
            colorOfCover,
            language
        );
        return newArray;
    }

    // optional parameterized constructor
    public static Book[] optional_constructor(Book[] arr) {
        System.out.println("Adding a new book using the optional parameterized constructor.");

        // read all the parameters from the user
        System.out.println("Enter the book's name:");
        String name = inString();
        
        System.out.println("Enter the book's author:");
        String author = inString();
        
        System.out.println("Enter the book's price:");
        double price = inDouble();
        
        System.out.println("Enter the number of copies:");
        int numOfCopies = inInt();
        
        int[] qualityOfCopies = new int[numOfCopies];
        for(int i = 0; i < numOfCopies;) {
            System.out.println("Enter the quality of copy " + (i + 1) + " (0-10):");
            int curentNumber = inInt();
            if (curentNumber <= 10 && curentNumber >=0) {
                qualityOfCopies[i] = curentNumber;
                i++;
            }
            else {
                System.out.println("Invalid value! (0-10)");
            }
        }

        // work with the array
        Book[] newArray = new Book[arr.length + 1];
        for(int i = 0; i < arr.length; i++) {
            newArray[i] = arr[i];
        }

        // create a new book object and add it to the new array
        newArray[arr.length] = new Book(
            name,
            author,
            price,
            numOfCopies,
            qualityOfCopies
        );
        return newArray;
    }

    // random constructor
    public static Book[] random_constructor(Book[] arr) {
        System.out.println("Adding a new book using the random parameterized constructor.");

        // read all the parameters from the user
        String[] randomNames = {"Smerti na Nile", "Gosudari", "Golodnii igri", "Taras Bulba", "Rukovodstvo mastera podzemeliy"};
        int randomIndexForName = (new Random()).nextInt(randomNames.length);
        String name = randomNames[randomIndexForName];
        
        String[] randomAuthors = {"Agata Kristi", "Nikolo Makkiavelli", "Suzanne Collins", "Nikolai Gogol", "Wizards"};
        int randomIndexForAuthor = (new Random()).nextInt(randomAuthors.length);
        String author = randomAuthors[randomIndexForAuthor];

        double price = (new Random()).nextDouble() * 100;

        int numOfCopies = (new Random()).nextInt(100) + 1;

        int[] qualityOfCopies = new int[numOfCopies];
        for(int i = 0; i < numOfCopies;) {
            int curentNumber = ((new Random()).nextInt(11));
            qualityOfCopies[i] = curentNumber;
            i++;
        }
        
        int numOfPages = (new Random()).nextInt(1000) + 1;
        
        String[] randomGenres = {"Detective", "Mail", "Fantasy", "Povest", "Guide"};
        int randomIndexForGenre = (new Random()).nextInt(randomGenres.length);
        String genre = randomGenres[randomIndexForGenre];

        String[] randomColors = {"Black", "Blue", "Red", "Green", "Purple"};
        int randomIndexForColor = (new Random()).nextInt(randomColors.length);
        String colorOfCover = randomColors[randomIndexForColor];


        String[] randomLanguages = {"French", "Italian", "English", "Russian", "American"};
        int randomIndexForLanguage = (new Random()).nextInt(randomLanguages.length);
        String language = randomLanguages[randomIndexForLanguage];

        // work with the array
        Book[] newArray = new Book[arr.length + 1];
        for(int i = 0; i < arr.length; i++) {
            newArray[i] = arr[i];
        }

        // create a new book object and add it to the new array
        newArray[arr.length] = new Book(
            name,
            author,
            price,
            numOfCopies,
            qualityOfCopies,
            numOfPages,
            genre,
            colorOfCover,
            language
        );
        return newArray;
    }

    // randommaize the object
    public Book randomaize_the_object() {
        System.out.println("Adding a new book using the random parameterized constructor.");

        // read all the parameters from the user
        String[] randomNames = {"Smerti na Nile", "Gosudari", "Golodnii igri", "Taras Bulba", "Rukovodstvo mastera podzemeliy"};
        int randomIndexForName = (new Random()).nextInt(randomNames.length);
        String name = randomNames[randomIndexForName];
        
        String[] randomAuthors = {"Agata Kristi", "Nikolo Makkiavelli", "Suzanne Collins", "Nikolai Gogol", "Wizards"};
        int randomIndexForAuthor = (new Random()).nextInt(randomAuthors.length);
        String author = randomAuthors[randomIndexForAuthor];

        double price = (new Random()).nextDouble() * 100;

        int numOfCopies = (new Random()).nextInt(100) + 1;

        int[] qualityOfCopies = new int[numOfCopies];
        for(int i = 0; i < numOfCopies;) {
            int curentNumber = ((new Random()).nextInt(11));
            qualityOfCopies[i] = curentNumber;
            i++;
        }
        
        int numOfPages = (new Random()).nextInt(1000) + 1;
        
        String[] randomGenres = {"Detective", "Mail", "Fantasy", "Povest", "Guide"};
        int randomIndexForGenre = (new Random()).nextInt(randomGenres.length);
        String genre = randomGenres[randomIndexForGenre];

        String[] randomColors = {"Black", "Blue", "Red", "Green", "Purple"};
        int randomIndexForColor = (new Random()).nextInt(randomColors.length);
        String colorOfCover = randomColors[randomIndexForColor];


        String[] randomLanguages = {"French", "Italian", "English", "Russian", "American"};
        int randomIndexForLanguage = (new Random()).nextInt(randomLanguages.length);
        String language = randomLanguages[randomIndexForLanguage];

        this.name = name;
        this.author = author;
        this.price = price;
        this.numOfCopies = numOfCopies;
        this.qualityOfCopies = qualityOfCopies;
        this.genre = genre;
        this.colorOfCover = colorOfCover;
        this.language = language;

        return this;
    }

    // copy constructor
    public static Book[] copy_constructor(Book[] arr) {
        if (arr.length == 0) {
            System.out.println("No books available to copy.");
            return arr;
        }
        Book bookToFind = find_a_book(arr);
        if (bookToFind == null) {
            System.out.println("Copying cancelled.");
            return arr; // Возвращаем исходный массив без изменений
        }
    
        System.out.println("Adding a new book using the copy constructor.");
        Book[] newArray = new Book[arr.length + 1];
        for (int i = 0; i < arr.length; i++) {
            newArray[i] = arr[i];
        }
        newArray[arr.length] = new Book(bookToFind);
        return newArray;
    }
    
    // find a book
    public static Book find_a_book(Book[] arr) {
        System.out.println("Choose a method to find the book to copy:");
        System.out.println("1. Find by index");
        System.out.println("2. Find by name");
        System.out.println("3. Cancel");

        switch (inString()) {
            case "1":
                System.out.println("Enter the index of the book to copy (0 to " + (arr.length - 1) + "):");
                int index = inInt();
                return find_book_by_index(arr, index);
            case "2":
                System.out.println("Enter the name of the book to copy:");
                String name = inString();
                return find_book_by_name(arr, name);
            case "3":
                return null;
            default:
                System.out.println("Invalid option. Please try again.");
                return null;
        }
    }

    // find a book by index
    public static Book find_book_by_index(Book[] arr, int index) {
        if (index >= 0 && index < arr.length) {
            return arr[index];
        } else {
            System.out.println("Invalid index.");
            return null;
        }
    }

    // find a book by name
    public static Book find_book_by_name(Book[] arr, String name) {
        for (int i = 0; i < arr.length; i++) {
            if (arr[i].get_name().equals(name)) {
                return arr[i];
            }
        }
        System.out.println("Book not found.");
        return null;
    }

    public void book_description() {
        System.out.println("name: " + this.name);
        System.out.println("author: " + this.author);
        System.out.println("price: " + this.price);
        System.out.println("number of copies: " + this.numOfCopies);
        if(this.numOfCopies > 0) {
            System.out.print("quality of copies: ");
            for(int i = 0; i < this.numOfCopies; i++) {
                System.out.print(" " + this.qualityOfCopies[i]);
            }
        }
        else {
            System.out.print("quality of copies: Unknown");
        }
        System.out.println();
        System.out.println("number of pages: " + this.numOfPages);
        System.out.println("genre: " + this.genre);
        System.out.println("color of cover: " + this.colorOfCover);
        System.out.println("language: " + this.language);
    }

    public static double get_the_popularity(Book book) {
        if (book == null || book.get_num_of_copies() <= 0 || book.get_quality_of_copies() == null) {
            return 0.0;
        }
        int sum = 0;
        for(int i = 0; i < book.get_num_of_copies(); i++) {
            sum += book.get_quality_of_copies()[i];
        }
        if (book.get_num_of_copies() == 0) return 0.0;
        else return (double) sum  / book.get_num_of_copies();
    }

    public static double get_the_cost(Book book) {
        return book.get_price() * book.get_num_of_copies();
    }

    // Getters and Setters
    public static int get_numOfBooks() {
         return numOfBooks;
    }
    public String get_name() {
        return name;
    }
    public void set_name(String name) {
        this.name = name;
    }
    public String get_author() {
        return author;
    }
    public void set_author(String author) {
        this.author = author;
    }
    public double get_price() {
        return price;
    }
    public void set_price(double price) {
        this.price = price;
    }
    public int get_num_of_copies() {
        return numOfCopies;
    }
    public void set_num_of_copies(int numOfCopies) {
        if (numOfCopies > 0 && numOfCopies != this.numOfCopies && numOfCopies < 100) {
            int tmp[] = new int[this.numOfCopies];
            for(int i = 0; i < this.numOfCopies; i++) {
                tmp[i] = this.qualityOfCopies[i];
            }
            this.qualityOfCopies = new int[numOfCopies];
            int min = (numOfCopies < this.numOfCopies) ? numOfCopies : this.numOfCopies;
            for(int i = 0; i < min; i++) {
                this.qualityOfCopies[i] = tmp[i];
            }
            this.numOfCopies = numOfCopies;
        }
    }
    public int[] get_quality_of_copies() {
        return qualityOfCopies;
    }
    public void set_quality_of_copies(int[] qualityOfCopies) {
    if (qualityOfCopies != null && qualityOfCopies.length > 0 && qualityOfCopies.length < 100) {
        
        boolean allValid = true;
        for (int i = 0; i < qualityOfCopies.length; i++) {
            if (qualityOfCopies[i] < 0 || qualityOfCopies[i] > 10) {
                allValid = false;
                break;
            }
        }
        if (allValid) {
            int newSize = qualityOfCopies.length;

            this.qualityOfCopies = new int[newSize];
            for (int i = 0; i < newSize; i++) {
                this.qualityOfCopies[i] = qualityOfCopies[i];
            }
            this.numOfCopies = newSize;
        }
    }
}
    public int get_num_of_pages() {
        return numOfPages;
    }
    public void set_num_of_pages(int numOfPages) {
        this.numOfPages = numOfPages;
    }
    public String get_genre() {
        return genre;
    }
    public void set_genre(String genre) {
        this.genre = genre;
    }
    public String get_color_of_cover() {
        return colorOfCover;
    }
    public void set_color_of_cover(String colorOfCover) {
        this.colorOfCover = colorOfCover;
    }
    public String get_language() {
        return language;
    }
    public void set_language(String language) {
        this.language = language;
    }

    // for 10

    // constructor with file name
    public Book(String fileName) {
    try {
        BufferedReader box = new BufferedReader(new FileReader(fileName));

        this.name = box.readLine();
        this.author = box.readLine();
        this.price = Double.valueOf(box.readLine()).doubleValue();
        this.numOfCopies = Integer.valueOf(box.readLine()).intValue();

        this.qualityOfCopies = new int[this.numOfCopies];
        for (int i = 0; i < this.numOfCopies; i++) {
            this.qualityOfCopies[i] = Integer.valueOf(box.readLine()).intValue();
        }

        this.numOfPages = Integer.valueOf(box.readLine()).intValue();
        this.genre = box.readLine();
        this.colorOfCover = box.readLine();
        this.language = box.readLine();

        numOfBooks++;
        System.out.println("Book is downloaded successfully from file: " + fileName);
        
        box.close();
    } catch (IOException e) {
        System.out.println("Error via reading file: " + e.getMessage());
    } catch (NumberFormatException e) {
        System.out.println("Format error: " + e.getMessage());
    }
}

    // save to file function
    public void saveToFile(String fileName) {
        try (BufferedWriter writer = new BufferedWriter(new FileWriter(fileName))) {
            writer.write(this.name + "\n");
            writer.write(this.author + "\n");
            writer.write(this.price + "\n");
            writer.write(this.numOfCopies + "\n");

            for (int i = 0; i < this.numOfCopies; i++) {
                writer.write(this.qualityOfCopies[i] + "\n");
            }

            writer.write(this.numOfPages + "\n");
            writer.write(this.genre + "\n");
            writer.write(this.colorOfCover + "\n");
            writer.write(this.language + "\n");

            System.out.println("Данные книги \"" + this.name + "\" сохранены в файл: " + fileName);
        } catch (IOException e) {
            System.out.println("Ошибка при сохранении в файл: " + e.getMessage());
        }
    }
}