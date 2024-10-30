package ial.aufgaben;

public class Schaltjahr1 {

	public static void main(String[] args) {
        int jahr = 2024;    
        int monat = 2;      

        int tage;

        switch (monat) {
            case 1: case 3: case 5: case 7: case 8: case 10: case 12:
                tage = 31;
                break;
            case 4: case 6: case 9: case 11:
                tage = 30;
                break;
            case 2:
                
                if ((jahr % 4 == 0 && jahr % 100 != 0) || (jahr % 400 == 0)) {
                    tage = 29;  // februar hat 29 Tage 
                } else {
                    tage = 28;  // Februar hat 28 Tage 
                }
                break;
            default:
                System.out.println("Ungültiger Name!!!! ");
                return;  
        }

        System.out.println("Der Monat " + monat + " im Jahr " + jahr + " hat " + tage + " Tage");
    }
}