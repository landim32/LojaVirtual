using SQLite;

namespace Radar
{
    public interface ISQLite
    {
        SQLiteConnection GetConnection();
    }
}
