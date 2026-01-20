public class SecureVariablesLab
{
	int k, j;
	int result;
	private int count;
	static private final int max_count;
	public void counter ()
	{
		count = 0;
		while (condition ())
			{
				max_count = 500;
				if (count++ > max_count) return;
			}
	}
/* no other method references count */
/* but several other methods reference MAX_COUNT */
	private void doLogic()
		{
			int i=0;
			for (i=0; i<10; i++)
				{
					/* ... */
				}	
			for (int i=0; i<20; i++)
				{
					/* ... */
				}
		}
}
