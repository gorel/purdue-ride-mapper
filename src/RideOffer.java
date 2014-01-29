/**
  * File: RideOffer.java
  * Last Modified: 1/29/2014
  * @author Logan Gore
  * @see Ride
  * This class represents a RideOffer that would be given by someone offering a ride to other students.
  * The constructor is almost identical to the abstract {@link Ride} class, but also includes an integer number of seats available.
  * The student must be offering at least one seat, or an IllegalArgumentException will be thrown.
  */
import java.util.Date;

public class RideOffer extends Ride
{
	protected int seatsAvailable;		//The number of seats available 
	
	/**
	  * Construct a RideOffer object with the given opening and closing time window for leaving,
	  * the given start and end locations, and the optional description.
	  * @param openWindow The earliest time that the user wishes to leave
	  * @param closeWindow The latest time the user wishes to leave
	  * @param startLoc The location the user wishes to leave from
	  * @param endLoc The location the user wishes to arrive at
	  * @param description An optional field of extra information the user wishes to list
	  * @param numSeats The number of seats the user is offering
	  * @throws IllegalArgumentException if the user did not specify a valid number of open seats
	  */
	public RideOffer(Date openWindow, Date closeWindow, String startLoc, String endLoc, String description, int numSeats) throws IllegalArgumentException
	{
		if (numSeats < 1)
			throw new IllegalArgumentException("Error: User must offer at least one open seat.");
		
		super(openWindow, closeWindow, startLoc, endLoc, description);
		this.seatsAvailable = numSeats;
	}
	
	//Getter methods
	public int getSeatsAvailable()
	{
		return seatsAvailable;
	}
}