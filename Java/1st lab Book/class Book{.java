import java.util.Scanner;

class Book{
    private static int numOfBooks = 0;
    private String name = "Unknown";
    private String author = "Unknown";
    private double price = 0.0;    
    private int numOfCopies = 0;
    private byte qualityOfCopies[];
    private int numOfPages = 0;                 //optional
    private String genre = "Unknown";           //optional
    private String colorOfCover = "Unknown";    //optional
    private String language = "Unknown";        //optional

    public Book() {
        numOfBooks++;
    }

    public Book(
        String name,
        String author,
        double price,
        int numOfCopies, 
        byte qualityOfCopies[])
        {
        this.name = name;
        this.author = author;
        this.price = price;
        this.numOfCopies = numOfCopies;
        this.qualityOfCopies = qualityOfCopies.clone();
        numOfPages = 0;                         //optional
        genre = "Unknown";                      //optional
        colorOfCover = "Unknown";               //optional
        language = "Unknown";                   //optional

        numOfBooks++;
        }

    public Book(
        String name,
        String author,
        double price,
        int numOfCopies, 
        byte qualityOfCopies[],
        int numOfPages,
        String genre,
        String colorOfCover,
        String language)
        {
        this.name = name;
        this.author = author;
        this.price = price;
        this.numOfCopies = numOfCopies;
        this.qualityOfCopies = qualityOfCopies.clone();
        this.numOfPages = numOfPages;
        this.genre = genre;
        this.colorOfCover = colorOfCover;
        this.language = language;

        numOfBooks++;
        }

    public Book(Book otherBook){
        this.name = otherBook.name;
        this.author = otherBook.author;
        this.price = otherBook.price;
        this.numOfCopies = otherBook.numOfCopies;
        if (otherBook.qualityOfCopies != null) {
            this.qualityOfCopies = (qualityOfCopies != null) ? qualityOfCopies.clone() : new byte[0];;
        } else {
            this.qualityOfCopies = null;
        }
        this.numOfPages = otherBook.numOfPages;
        this.genre = otherBook.genre;
        this.colorOfCover = otherBook.colorOfCover;
        this.language = otherBook.language;

        numOfBooks++;
        }

    static Scanner scanner = new Scanner(System.in); 
    
    
    
    
    
    /////////////////////////////

    public static void main(String[] args) {
        Book arr[] = new Book[0];
        while(true) {
            System.out.println("Choose an option:");
            System.out.println("1. Add a book");
            System.out.println("2. Display book information");
            System.out.println("3. Exit");

        switch (scanner.nextLine()) {
            case "1":
                arr = Book.constructor(arr);
                break;
            case "2":
                if (arr.length == 0) {
                    System.out.println("No books available.");
                } 
                else{
                    System.out.println("Enter the index of the book to display (0 to " + (arr.length - 1) + "):");
                    int index = Integer.parseInt(scanner.nextLine());
                    if (index >= 0 && index < arr.length) {
                        arr[index].book_description();
                    } else {
                        System.out.println("Invalid index.");
                    }
                }
                break;
            case "3":
                System.exit(0);
                break;
            default:
                System.out.println("Invalid option. Please try again.");
                break;
            }
        }
    }

    public static Book[] constructor(Book[] arr) {
        
        System.out.println("Enter the book's name:");
        String name = scanner.nextLine();
        
        System.out.println("Enter the book's author:");
        String author = scanner.nextLine();
        
        System.out.println("Enter the book's price:");
        double price = Double.parseDouble(scanner.nextLine());
        
        System.out.println("Enter the number of copies:");
        int numOfCopies = Integer.parseInt(scanner.nextLine());
        
        byte[] qualityOfCopies = new byte[numOfCopies];
        for(int i = 0; i < numOfCopies;) {
            System.out.println("Enter the quality of copy " + (i + 1) + " (0-10):");
            byte curentNumber = Byte.parseByte(scanner.nextLine());
            if (curentNumber <= 10 && curentNumber >=0) {
                qualityOfCopies[i] = curentNumber;
                i++;
            }
            else {
                System.out.println("Invalid value! (0-10)");
            }
        }
        
        System.out.println("Enter the number of pages:");
        int numOfPages = Integer.parseInt(scanner.nextLine());
        
        System.out.println("Enter the book's genre:");
        String genre = scanner.nextLine();
        
        System.out.println("Enter the color of the cover:");
        String colorOfCover = scanner.nextLine();
        
        System.out.println("Enter the book's language:");
        String language = scanner.nextLine();

        Book[] newArray = new Book[arr.length + 1];
        for(int i = 0; i < arr.length; i++) {
            newArray[i] = arr[i];
        }
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
    
    public void book_description() {
        System.out.println("name:" + this.name);
        System.out.println("author:" + this.author);
        System.out.println("price:" + this.price);
        System.out.println("number of copies:" + this.numOfCopies);
        if(this.numOfCopies > 0) {
            System.out.print("quality of copies:");
            for(int i = 0; i < this.numOfCopies; i++) {
                System.out.print(" " + this.qualityOfCopies[i]);
            }
        }
        else {
            System.out.print("quality of copies: Unknown");
        }
        System.out.println();
        System.out.println("number of pages:" + this.numOfPages);
        System.out.println("genre:" + this.genre);
        System.out.println("color of cover:" + this.colorOfCover);
        System.out.println("language:" + this.language);
    }





    public void number_of_books() {
        System.out.println("total number of books:" + this.get_numOfBooks());
    }

    private int get_numOfBooks() {
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
            byte tmp[] = new byte[this.numOfCopies];
            for(int i = 0; i < this.numOfCopies; i++) {
                tmp[i] = this.qualityOfCopies[i];
            }
            this.qualityOfCopies = new byte[numOfCopies];
            int min = (numOfCopies < this.numOfCopies) ? numOfCopies : this.numOfCopies;
            for(int i = 0; i < min; i++) {
                this.qualityOfCopies[i] = tmp[i];
            }
            this.numOfCopies = numOfCopies;
        }
    }

    public byte[] get_quality_of_copies() {
        return qualityOfCopies;
    }

    public void set_quality_of_copies(byte[] qualityOfCopies) {
        this.qualityOfCopies = qualityOfCopies.clone();
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
}