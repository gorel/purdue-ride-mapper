import java.util.Date;

public class RideOffer extends Ride
{
	private int seatsAvailable;		//The number of seats available 
	
	public RideOffer(Date openWindow, Date closeWindow, String startLoc, String endLoc, int numSeats)
	{
		super(openWindow, closeWindow, startLoc, endLoc);
		seatsAvailable = numSeats;
	}
}