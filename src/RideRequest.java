import java.util.Date;

public class RideRequest extends Ride
{
	protected int searchRadius;

	public RideRequest(Date openWindow, Date closeWindow, String startLoc, String endLoc, int radius)
	{
		super(openWindow, closeWindow, startLoc, endLoc);
		this.searchRadius = radius;
	}
}