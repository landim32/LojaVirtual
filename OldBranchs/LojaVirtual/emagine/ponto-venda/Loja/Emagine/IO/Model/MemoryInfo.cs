using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Model
{
    public class MemoryInfo
    {
        public long FreeMemory { get; set; }
        public long MaxMemory { get; set; }
        public long TotalMemory { get; set; }

        public long UsedMemory
        {
            get
            {
                return (TotalMemory - FreeMemory);
            }
        }

        public double HeapUsage()
        {
            return (double)(UsedMemory) / (double)TotalMemory;
        }

        public double Usage()
        {
            return (double)(UsedMemory) / (double)MaxMemory;
        }

        public override string ToString()
        {
            const double mb = 1024 * 1024;
            double percentUsed = (double)(MaxMemory - FreeMemory) / (double)MaxMemory;
            return string.Format("livre: {0:N1}mb, usado: {1:N1}mb, max: {2:N1}mb, usado: {3:P}", FreeMemory / mb, UsedMemory / mb, MaxMemory / mb, percentUsed);
        }
    }
}
