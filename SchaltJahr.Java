package ial.aufgaben;

public class schaltjahr {
	public static void main(String[] args) {
        int n2012 = 2012;  

        
        if (n2012 % 4 == 0) {
            if (n2012 % 100 == 0) {
                if (n2012 % 400 == 0) {
                    System.out.println("Schaltjahr!");
                } else {
                    System.out.println("Kein Schaltjahr!");
                }
            } else {
                System.out.println("Schaltjahr!");
            }
        } else {
            System.out.println("Kein Schaltjahr!");
        }
    }
}
