interface Ужас {
    void кукожиться();
}
abstract class Снусмумрик implements Ужас {
    public static int objectCount = 0;

    protected int countFarikov;
    protected float[] weights;

    public Снусмумрик() {
        this.countFarikov = 0;
        this.weights = new float[0];
        objectCount++;
    }

    public Снусмумрик(int count) {
        if (count < 0) {
            count = 0;
        }
        this.countFarikov = count;
        this.weights = new float[count];
        objectCount++;
    }

    public Снусмумрик(Снусмумрик other) {
        if (other != null) {
            this.countFarikov = other.countFarikov;
            if (other.weights != null) {
                this.weights = new float[other.countFarikov];
                // ручное копирование элементов массива
                for (int i = 0; i < other.countFarikov; i++) {
                    this.weights[i] = other.weights[i];
                }
            } else {
                this.weights = new float[0];
            }
        } else {
            this.countFarikov = 0;
            this.weights = new float[0];
        }
        objectCount++;
    }

    public void setCountFarikov(int newCount) {
        if (newCount < 0) {
            newCount = 0;
        }
        
        // Перевыделяем память (создаём новый массив нового размера)
        float[] newWeights = new float[newCount];
        
        // Сохраняем старые данные, если они были
        if (this.weights != null) {
            int copyLength = Math.min(this.countFarikov, newCount);

            for (int i = 0; i < copyLength; i++) {
                newWeights[i] = this.weights[i];
            }
        }

        this.countFarikov = newCount;
        this.weights = newWeights; // Заменяем ссылку на новый массив
    }

    public int getCountFarikov() {
        return countFarikov;
    }

    public abstract void print();
}

class Марфуфочка extends Снусмумрик {
    private short tailLength;

    public Марфуфочка(int count, short tailLength) {
        super(count); // Вызов параметризованного конструктора родительского класса
        this.tailLength = tailLength;
    }

    public Марфуфочка() {
        super();
        this.tailLength = 0;
    }

    public void print() {
        System.out.println("Марфуфочка: количество фариков = " + countFarikov + ", длина хвоста = " + tailLength + " см.");
        System.out.print("Веса фариков: ");
        if (weights != null && weights.length > 0) {
            for (float weight : weights) {
                System.out.print(weight + " кг; ");
            }
            System.out.println();
        } else {
            System.out.println("фариков нет.");
        }
    }

    public void кукожиться() {
        System.out.println("Марфуфочка с хвостом " + tailLength + " см страшно кукожится!");
    }

    public short getTailLength() {
        return tailLength;
    }

    public void setTailLength(short tailLength) {
        this.tailLength = tailLength;
    }
}

public class Main {
    public static void main(String[] args) {
        // Создаём массив типа родительского класса (Снусмумрик[])
        Снусмумрик[] array = new Снусмумрик[3];

        // Заполняем массив объектами класса-наследника (Марфуфочка)
        array[0] = new Марфуфочка(2, (short) 15);
        array[1] = new Марфуфочка(4, (short) 25);
        array[2] = new Марфуфочка(1, (short) 8);

        // Демонстрация работы методов и полиморфизма
        System.out.println("=== Проверка массива объектов ===");
        for (int i = 0; i < array.length; i++) {
            System.out.println("\n[Объект №" + (i + 1) + "]");
            array[i].print();        // Вызов метода print()
            array[i].кукожиться();  // Вызов метода интерфейса
        }

        // Проверка работы статической переменной (пункт 1)
        System.out.println("\n-------------------------------------------");
        System.out.println("Всего создано объектов Снусмумрик: " + Снусмумрик.objectCount);

        // Проверка работы метода set с перевыделением памяти (пункт 5)
        System.out.println("\n=== Проверка метода set (изменение размера массива) ===");
        System.out.println("До изменения:");
        array[0].print();

        // Увеличиваем количество фариков с 2 до 5
        array[0].setCountFarikov(5);
        System.out.println("После вызова setCountFarikov(5):");
        array[0].print();
    }
}