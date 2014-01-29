public abstract class Ride
{
	protected Date departureOpen;		//The opening window of when the person offering the ride will be leaving
	protected Date departureClose;		//The closing window of when the person offering the ride will be leaving
	protected String description;		//A user's personal description if they wish to give one
	
	//These are string objects for now.  We will replace with the appropriate Map API when we decide on one
	//Note: Either startLocation or endLocation must be Purdue.
	private String	startLocation;
	private String	endLocation;
	
	public RideOffer(Date openWindow, Date closeWindow, String startLoc, String endLoc)
	{
		this.departureOpen = openWindow;
		this.departureClose = closeWindow;
		this.startLocation = startLoc;
		this.endLocation = endLoc;
		this.description = "";
	}
	
	/**
	  * Determine whether or not the given Dates occur within the same window as this ride offer's.
	  * If thisStart > otherEnd or thisEnd < otherStart, the dates do not overlap.
	  * By de Morgan's law, the overlap test simplifies to thisStart <= otherEnd && thisEnd >= otherStart.
	  * @param otherStart The start time of the Date to compare to
	  * @param otherEnd The end time of the Date to compare to
	  * @return whether or not this ride's departure window overlaps with the given other date
	  */
	public boolean inRange(Date otherStart, Date otherEnd)
	{
		return (departureOpen.compareTo(otherEnd) < 1)
				&& (otherStart.compareTo(departureClose) < 1);
	}
}