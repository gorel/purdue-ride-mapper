/**
  * File: Ride.java
  * Last Modified: 1/29/2014
  * @author Logan Gore
  * This is a generic Ride class used to create RideRequests and RideOffers.
  * Each ride has a departure time window, a start and end location, and a description field which may be empty.
  * This class also has a method to determine if two dates overlap.
  * If the closeWindow occurs before the openWindow, an IllegalArgumentException will be thrown.
  * Note: This is an abstract class not meant to be instantiated -- create a RideOffer or RideRequest to specify the type of ride needed.
  */
import java.util.Date;

public abstract class Ride
{
	protected Date departureOpen;		//The opening window of when the person offering the ride will be leaving
	protected Date departureClose;		//The closing window of when the person offering the ride will be leaving
	protected String description;		//A user's personal description of the trip if they wish to give one
	
	//These are String objects for now.  We will replace with the appropriate Map API when we decide on one
	//Note: Either startLocation or endLocation must be Purdue.
	private String	startLocation;
	private String	endLocation;
	
	/**
	  * Construct a RideOffer object with the given opening and closing time window for leaving,
	  * the given start and end locations, and the optional description.
	  * @param openWindow the earliest time that the user wishes to leave
	  * @param closeWindow the latest time the user wishes to leave
	  * @param startLoc the location the user wishes to leave from
	  * @param endLoc the location the user wishes to arrive at
	  * @param description an optional field of extra information the user wishes to list
	  * @throws IllegalArgumentException if closeWindow < openWindow
	  */
	public RideOffer(Date openWindow, Date closeWindow, String startLoc, String endLoc, String description) throws IllegalArgumentException
	{
		if (closeWindow.compareTo(openWindow) < 1)
			throw new IllegalArgumentException("Error: Ending departure time must occur after starting departure time.");
		
		this.departureOpen = openWindow;
		this.departureClose = closeWindow;
		this.startLocation = startLoc;
		this.endLocation = endLoc;
		this.description = "";
	}
	
	//Getter methods
	public Date getOpen()
	{
		return departureOpen;
	}
	
	public Date getClose()
	{
		return departureClose;
	}
	
	public String getDescription()
	{
		return description;
	}
	
	/**
	  * Determine whether or not the given Dates occur within the same window as this ride offer's.
	  * If thisStart > otherEnd or thisEnd < otherStart, the dates do not overlap.
	  * By de Morgan's law, the overlap test simplifies to thisStart <= otherEnd && thisEnd >= otherStart.
	  * @param otherStart The date we are comparing against
	  * @return whether or not this ride's departure window overlaps with the given other date
	  */
	public boolean dateInRange(Ride other)
	{
		return (departureOpen.compareTo(other.getClose()) < 1)
				&& (other.getOpen().compareTo(departureClose) < 1);
	}
}