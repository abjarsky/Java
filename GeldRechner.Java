package ial.aufgaben;

public class GeldscheinRechner {

    public static void main(String[] args) {
        int betrag = 886;

        int fuenfhundert = betrag / 500;
        betrag %= 500;

        int zweihundert = betrag / 200;
        betrag %= 200;

        int hundert = betrag / 100;
        betrag %= 100;

        int fuenfzig = betrag / 50;
        betrag %= 50;

        int zwanzig = betrag / 20;
        betrag %= 20;

        int zehn = betrag / 10;
        betrag %= 10;

        int fuenf = betrag / 5;
        int rest = betrag % 5;

        System.out.println("der Geldbetrag: 886 EUR");
        System.out.println("500er-Scheine: " + fuenfhundert);
        System.out.println("200er-Scheine: " + zweihundert);
        System.out.println("100er-Scheine: " + hundert);
        System.out.println("50er-Scheine: " + fuenfzig);
        System.out.println("20er-Scheine: " + zwanzig);
        System.out.println("10er-Scheine: " + zehn);
        System.out.println("5er-Scheine: " + fuenf);
        System.out.println("Rest: " + rest);
    }
}
