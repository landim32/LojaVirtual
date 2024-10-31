using SQLite;

namespace Emagine.IBLL
{
    public interface ISQLite
    {
        SQLiteConnection GetConnection();
    }
}
