'''Read the pixel at position (row,col) from the file.'''


from array import array
import numpy.oldnumeric as Numeric

col = 258
row = 681
''' este punto tiene en qgis valor 0.63562 '''

fid = 'd:\\Dropbox\\ecgs\\test\\assets\\data\\msbas\\EW\\RASTERS\\20030409e.bin.nvi'
nCols = 601
nRows = 781
format = 'f'
vals = array( format )
sampleSize = array(format).itemsize      
delta = 0
offset = row * nCols * sampleSize + col * sampleSize

nPixels = nRows * nCols

rowSize = sampleSize * nCols

f = open( fid, "rb" )

print 'posicion: ', row * rowSize + col * sampleSize
f.seek(row * rowSize \
       + col * sampleSize, 0)
vals.fromfile(f, 1) 

vals.byteswap()  
pixel = Numeric.array(vals.tolist())
print pixel