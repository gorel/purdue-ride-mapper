public class User
{
		private String myName;			//User's name
		private String myEduEmail;		//User's .edu email address, used for verification
		private String myDisplayEmail;		//Email to display to be contacted at
		private RideRequest[] myRequests;	//List of requests for rides
		private RideOffer[] myOffers;		//List of offers to drive
		private boolean myVerifiedUser;		//User cannot make offers or requests until they verify their email
		private boolean myShowEmail;		//Publically show email or use private messaging system?
		private double myScore;			//From 0-10, how good of a user he is
		
		public User (String name, String eduEmail)
		{
			this.myName = name;
			this.myEduEmail = eduEmail;
			this.myDisplayEmail = eduEmail;	//This can be changed later if the user does not want their email shown
			this.myVerifiedUser = false;	//The user must verify for this to become true.
			this.myShowEmail = true;	//By default show their email. This can be changed later if they want.		
		}
}
