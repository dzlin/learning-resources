### C# 连接 `sql server` 数据库

```asp.net
<%@ Import Namespace="System.Data.SqlClient" %>
<%
    List<string> usernames = new List<string>();
    string connStr = "server=127.0.0.1;uid=sa;pwd=123456;database=sdzhcx50";
    SqlConnection conn = new SqlConnection(connStr);
    try
    {
        conn.Open();
        string sql = "SELECT TOP 1000 RTrim([a_rymc]) a_rymc FROM [dbo].[gz_ffb_02] WHERE [id] = 1";
        SqlCommand cmd = new SqlCommand(sql, conn);
        SqlDataReader reader = cmd.ExecuteReader();
        while (reader.Read())
        {
            usernames.Add(reader["a_rymc"] as string);
        }
        reader.Close();
    }
    catch (Exception ex)
    {
        Response.Write("Failed. Error: " + ex.Message);
        Response.End();
    }
    conn.Close();
%>
```
