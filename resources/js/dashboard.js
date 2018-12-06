


function right() {
  if(document.getElementById("menu").style.display!="none")
    {document.getElementById("category").style.display="block";
     document.getElementById("menu").style.display ="none";
     document.getElementById("item").style.display= "none";
     }
  else if (document.getElementById("category").style.display!="none")
    {document.getElementById("item").style.display="block";
     document.getElementById("menu").style.display= "none";
     document.getElementById("category").style.display= "none";  }
                 }
function left() {
  if(document.getElementById("item").style.display!="none")
    {document.getElementById("category").style.display="block";
     document.getElementById("menu").style.display = "none";
     document.getElementById("item").style.display= "none";
    }
   else if (document.getElementById("category").style.display!="none")
    {document.getElementById("menu").style.display= "block";
     document.getElementById("item").style.display= "none";
    document.getElementById("category").style.display= "none";
    }
}
