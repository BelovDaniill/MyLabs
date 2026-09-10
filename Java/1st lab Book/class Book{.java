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
        book_description();
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
        numOfPages = 0;                        //optional
        genre = "Unknow";                      //optional
        colorOfCover = "Unknown";              //optional
        language = "Unknown";                  //optional

        numOfBooks++;
        book_description();
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
        book_description();
        }

    public Book( Book otherBook){
        this.name = otherBook.name;
        this.author = otherBook.author;
        this.price = otherBook.price;
        this.numOfCopies = otherBook.numOfCopies;
        this.qualityOfCopies = otherBook.qualityOfCopies.clone();
        this.numOfPages = otherBook.numOfPages;
        this.genre = otherBook.genre;
        this.colorOfCover = otherBook.colorOfCover;
        this.language = otherBook.language;

        numOfBooks++;
        book_description();
        }

    public void constructor() {
        Scanner scanner = new Scanner(System.in);
        String name = scanner.nextLine();
        String author = scanner.nextLine();
        double price = Double.parseDouble(scanner.nextLine());
        int numOfCopies = Integer.parseInt(scanner.nextLine());
        byte quality;
        for(int i; i < numOfCopies;) {
            byte curentNumber = Byte.parseByte(scanner.nextLine());
            if (curentNumber <= 10 || curentNumber >=0) {
            qualityOfCopies[i] = curentNumber;
            i++;
            }
            else {
                System.out.println("Invalid value! (0-10)");
            }
        }
        int numOfPages = Integer.parseInt(scanner.nextLine());
        String genre = scanner.nextLine();
        String colorOfCover = scanner.nextLine();
        String language = scanner.nextLine();
        scanner.close();
        Book(
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
    }
    
    public void book_description() {
        System.out.println("name:" + this.name);
        System.out.println("author:" + this.author);
        System.out.println("price:" + this.price);
        System.out.println("number of copies:" + this.numOfCopies);
        System.out.println("quality of copies:" + this.qualityOfCopies);
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
        this.numOfCopies = numOfCopies;
    }

    public byte[] get_quality_of_copies() {
        return qualityOfCopies;
    }

    public void set_quality_of_copies(byte[] qualityOfCopies) {
        this.qualityOfCopies = qualityOfCopies;
    }

    // --- NumOfPages ---
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