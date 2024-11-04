package ial.aufgaben;

public class Zahlenstraenge {

	public static void main(String[] args) {

        // Zahlenstrang: 0, 1, 2, 3, …, 10
        int i = 0;
        System.out.print("Zahlenstrang 1: ");
        while (i <= 10) {
            System.out.print(i);
            if (i < 10) System.out.print(", ");
            i++;
        }
        System.out.println();

        // Zahlenstrang: -10, -8, -6, …, +10
        int j = -10;
        System.out.print("Zahlenstrang 2: ");
        while (j <= 10) {
            System.out.print(j);
            if (j < 10) System.out.print(", ");
            j += 2;
        }
        System.out.println();

        // Zahlenstrang: 1, 2, 4, 8, … bis 512
        int k = 1;
        System.out.print("Zahlenstrang 3: ");
        while (k <= 512) {
            System.out.print(k);
            if (k < 512) System.out.print(", ");
            k *= 2;
        }
        System.out.println();
    }
}