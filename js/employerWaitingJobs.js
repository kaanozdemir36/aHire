import { initializeApp } from "https://www.gstatic.com/firebasejs/9.8.2/firebase-app.js";
//import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.8.2/firebase-analytics.js";
import { getDatabase, set, ref, update, onValue, get,child } from "https://www.gstatic.com/firebasejs/9.8.2/firebase-database.js";
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/9.8.2/firebase-auth.js";



const firebaseConfig = {
    apiKey: "AIzaSyBExhGEGFVitugDVDoNYW2c4qeDyVQdqJQ",
    authDomain: "ahire-636cc.firebaseapp.com",
    databaseURL: "https://ahire-636cc-default-rtdb.firebaseio.com",
    projectId: "ahire-636cc",
    storageBucket: "ahire-636cc.appspot.com",
    messagingSenderId: "454483553062",
    appId: "1:454483553062:web:ccd511b854b9535adf2fe7",
    measurementId: "G-CM18EJXDBR"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  //const analytics = getAnalytics(app);
  const auth = getAuth(app);
  const database = getDatabase(app);
  const dbRef = ref(getDatabase());
  var tmp=0;



  
function GetAll(tmp, count,usermail){
 
while(tmp<count){
   
  const Jobs = ref(database, 'Jobs/'+tmp);

  onValue(Jobs, (snapshot) => {
    
  
    const data = snapshot.val();
    
    var id=data.count;

    if(usermail==data.mail){
      if(data.isDone==false && data.isStarted==false){
   
  
    var row = "<tr> <td>" +  snapshot.val().title + "</td> <td>" + snapshot.val().category + "</td> <td>" + snapshot.val().date + "</td>  <td>" + snapshot.val().price + "</td> <td>" +
     "<button type='button' class='btn btn-info' id='button'>SEE CANDIDATES</button>"+ "</td> </tr>" 
     $(row).appendTo('#jobs');
 
     var button = document.getElementById("button");
   
button.setAttribute("id",id);

      //buton içinde id hep en son count olarak dönüyor. Her buton aynı sayı dönüyor.
      
  
      
   button.addEventListener("click", function(event){
        console.log(button);
        window.localStorage.setItem("JobId",id);
       window.location.href = "/candidates.html"
       
     });
    }
    } 
 
  });

  tmp++;
 
  
}

  
 }
  tmp==0;
  //button onclick'te button id alsın
  

  
  const ar=[];
  //array.forEach(e => {
      
 // });
  // get jobs data from firebase
  let count;
  onAuthStateChanged(auth,(user)=>{
    
  if(user){
    
  user = auth.currentUser;
  get(child(dbRef, '/')).then((snapshot) => {
    
  
    if (snapshot.exists()) {
      count= Number(snapshot.val().JobCount);
      
      get(child(dbRef, 'users/'+user.uid+'/profile')).then((snapshot) => {
        const usermail=snapshot.val().mail
        GetAll(tmp,count,usermail);
      });

  
  
  
    } else {
      console.log("No jobcount data available");
    }
  }).catch((error) => {
    console.error(error);
  });



   
}

// kullanıcı giriş yapmamış olduğundan içeri giremez
  else{
   var path = window.location.pathname;
   var page = path.split("/").pop();
  
   if(page!=="login.html" && page!=="register.html"){
     window.location.href = "login.html"; 
   }
  
  
}

})