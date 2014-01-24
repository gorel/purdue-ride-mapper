import java.util.Date;

public class RideRequest extends Ride
{
	public RideRequest(Date openWindow, Date closeWindow, String startLoc, String endLoc)
	{
		super(openWindow, closeWindow, startLoc, endLoc);
	}
}