package ial.aufgaben;

public class AktivitaetDerWoche {

	public static void main(String[] args) {
		String wochentag = "Montag";
        String aktivitaet;

        switch (wochentag) {
        case "Montag":
            aktivitaet = "Einkaufen";
            break;
        case "Dienstag":
            aktivitaet = "Fortbildung";
            break;
        case "Donnerstag":
            aktivitaet = "Sport";
            break;
        case "Freitag":
            aktivitaet = "Party";
            break;
        case "Sonntag":
            aktivitaet = "Freunde treffen";
            break;
            
         default:
                aktivitaet = "Sich um den Haushalt kümmern";
                break;
        }

        System.out.println(aktivitaet);
    }


	
}
