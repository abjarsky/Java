package ial.aufgaben;

public class AusgabeA {
/*
	public static void main(String[] args) {
        for (int i = 0; i < 3; i++) {        
            for (int j = 0; j < 5; j++) {      
                System.out.print("X ");
            }
            System.out.println();               
        }
    }
}
*/
/*
public static void main(String[] args) {
    for (int i = 3; i > 0; i--) {          
        for (int j = 0; j < i; j++) {     
            System.out.print("X ");
        }
        System.out.println();               
    }
}
}
*/
public static void main(String[] args) {
    for (int row = 8; row >= 1; row--) {               
        for (char col = 'A'; col <= 'H'; col++) {      
            System.out.print(col + "" + row + " ");
        }
        System.out.println();                         
    }
}
}