
jQuery(document).ready(function() {

jQuery("#tbl").tableExport({

  // Displays table headers (th or td elements) in the <thead>
  headers: true,                    

  // Displays table footers (th or td elements) in the <tfoot>    
  footers: true, 

  // Filetype(s) for the export
  formats: ["csv"],           

  // Filename for the downloaded file
  fileName: "результаты",                         

  // Style buttons using bootstrap framework  
  bootstrap: true,

  // Automatically generates the built-in export buttons for each of the specified formats   
  exportButtons: true,                          

  // Position of the caption element relative to table
  position: "bottom",                   

  // (Number, Number[]), Row indices to exclude from the exported file(s)
  ignoreRows: null,                             

  // (Number, Number[]), column indices to exclude from the exported file(s)              
  ignoreCols: null,                   

  // Removes all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s)     
  trimWhitespace: false,

  // (Boolean), set direction of the worksheet to right-to-left (default: false)
  RTL: false, 

  // (id, String), sheet name for the exported spreadsheet, (default: 'id') 
  sheetname: "id" ,
  
  charset : "charset=utf-8"

});

});