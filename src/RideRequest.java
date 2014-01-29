/**
  * File: RideRequest.java
  * Last Modified: 1/29/2014
  * @author Logan Gore
  * @see Ride
  * This class represents a RideRequest that would be given by someone looking for a ride from other students.
  * The constructor is almost identical to the abstract {@link Ride} class,
  * but also includes an integer of how far away from the destination the user is willing to be dropped off.
  * The student must specify a radius greater than or equal to zero, or an IllegalArgumentException will be thrown.
  */
import java.util.Date;

public class RideRequest extends Ride
{
	protected int searchRadius;		//How far away from the destination the user is willing to be dropped off
	
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
	public RideRequest(Date openWindow, Date closeWindow, String startLoc, String endLoc, String description, int radius) throws IllegalArgumentException
	{
		if (radius < 0)
			throw new IllegalArgumentException("Error: Radius must be greater than or equal to zero.");
		
		super(openWindow, closeWindow, startLoc, endLoc);
		this.searchRadius = radius;
	}
	
	//Getter methods
	public int getSearchRadius()
	{
		return searchRadius;
	}
}