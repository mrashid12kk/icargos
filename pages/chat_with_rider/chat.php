<?php
session_start();
   require 'includes/conn.php';
 global $con;
$cus = $_SESSION['customers'];
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $order_no = $_POST["order_no"];
    $user_id = $_POST["user_id"];
    $order_id = $_POST["order_id"];
    $rider_type = $_POST["rider_type"];
     $sql1 = "SELECT * FROM chat_master WHERE order_number = '$order_no'  ";
    $result1 = mysqli_query($con, $sql1);
    $rowcount=mysqli_num_rows($result1);
    if($rowcount == 0){
       $sql  = "INSERT INTO `chat_master`(`customer_id`,`order_id`,`user_id`,`order_number`, `rider_type`) VALUES ('$cus','$order_id','$user_id','$order_no','$rider_type')";
       if( mysqli_query($con, $sql)){
           header('Location: ' . $_SERVER['HTTP_REFERER']);
       }else{
           echo "Error: " . $sql . "<br>" . mysqli_error($con);
       }
    }else{
        $_SESSION['error1'] = "Chat Already Available.";
       header('Location: ' . $_SERVER['HTTP_REFERER']);
        
        
        
    }
}

?>



<div class='msgs'></div>
<div class="popup_box">
    <?php if(isset($_SESSION['error1'])) {?>
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-success"><?php echo $_SESSION['error1']; ?></div>
        </div>
<?php unset($_SESSION['error1']); } ?>
      <div class=" clearfix">
		    <div class="people-list" id="people-list">
		      <div class="search">
		        <input type="text" id="search_input" placeholder="Search By Order_number">
		        <i class="fa fa-search"></i>
		      </div>
		      <ul class="list" id="list">
		         
		        
		      </ul>
		    </div>
		    
		    <div class="chat">
		      <div id="">
    		      <div class="chat-header clearfix">
    		        <p id="image"></p>
    		        
    		        <div class="chat-about">
    		          <div class="chat-with" id="name"></div>
    		          <!--<div class="chat-num-messages">already 1 902 messages</div>-->
    		        </div>
    		        <b id="order_no1"></b>
    		      </div> <!-- end chat-header -->
    		     
    		      
    		      <div class="chat-history">
    		          <div id="loader" style="display: none;  margin-left: 30rem;">
                        <!-- Loader content goes here -->
                        Loading...
                    </div>
                    <ul id="chat_box1">
    		          
    		         
    		          
    		        </ul>
    		        <ul id="chat_box">
    		          
    		         
    		          
    		        </ul>
    		        <div id="loader" style="display: none;">
                        <!-- Loader content goes here -->
                        Loading...
                    </div>
                    <div id="last_message" data-message-id="0" style="display: none;"></div>
    		        
    		      </div> <!-- end chat-history -->
		      </div>
		      <div class="chat-message clearfix">
		          <input type="hidden" id="chat_master_id" value="">
		          <input type="hidden" id="user" value="">
		        <textarea name="message-to-send" id="message-to-send" placeholder="Type your message" rows="3"></textarea>
		        <button onclick="sendMessage()" id="sendMessage">Send</button>
		        <button onclick="insert()" id="sendmass" style="display:none;">Send</button>

		      </div> <!-- end chat-message -->

		      
		      
		    </div> <!-- end chat -->
		    <div class="newchat_member">
		            <div class="close_popup">
		                <img src="assets/img/close-btn.png" alt="">
		            </div>
		          <h4>New Chat</h4>
		          <form id="orderForm" method="post">
		              <div class="input_form">
		                 <div class="alert alert-danger" style="display:none;" id="alert" role="alert">
                          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                          <span class="sr-only">Error:</span>
                          User Not Found
                        </div>
		                  <input type="text" placeholder="Enter Order Number" id="ordername"  name="order_number">	  
				        
						  
						  <select class="form-select form-control-lg" name="rider_type"      style="margin-bottom:1vw;padding:0.5vw;" id="selectoption"  >
      						<option selected>Select an option...</option>
      						<option value="pickup_rider" >Pickup_rider</option>
     						<option value="delivery_rider" >Delivery_rider</option>
							<option value="return_rider" >Return_rider</option>
    						</select>
		                  <button type="button" id="submitbutton">Submit</button>
		              </div>
		              <div class="order_status_box">
		                  <b>Order Number</b>
		                  <p id="ordernumber"></p>
		                  <b>Rider Name</b>
		                  <p id="rider"></p>
		                  <input type="hidden" name="order_id" id="order_id" value="">
		                  <input type="hidden" name="order_no" id="order_no" value="">
		                  <input type="hidden" name="customer" id="customer" value="">
		                  <input type="hidden" name="user_id" id="user_id" value="">
		                  <input type="hidden" name="rider_type" id="rider_type" value="">
		                  <input type="hidden" name="name" id="name" value="">
		                  <input type="hidden" name="c_name" id="c_name" value="">
		                  <input type="hidden" name="c_phone" id="c_phone" value="">
		              </div>
		              <div class="start_chat input_form">
		                  <button type="button" name="submit" style="display:none;" id="start_chat">Start Chat</button>
		                  <!--<a href="#">Start Chat</a>-->
		              </div>
		          </form>

		      </div>
		    
		  </div> <!-- end container -->

	

		</div>
		
		<script src="https://www.gstatic.com/firebasejs/10.1.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.1.0/firebase-database.js"></script>
<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyBJSmfQW_sNOXGwu7t2HpmdYDqCR37Ln5k",
    authDomain: "taslim-chat-app.firebaseapp.com",
    databaseURL: "https://taslim-chat-app-default-rtdb.firebaseio.com",
    projectId: "taslim-chat-app",
    storageBucket: "taslim-chat-app.appspot.com",
    messagingSenderId: "351181702741",
    appId: "1:351181702741:web:c7443cc8ff2b9c3473f6dc",
    measurementId: "G-LKENVVD62X"
  };

  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  var database = firebase.database();
  
  // Reference to your rider collection
  var contactsRef = database.ref('chats');
  
  // Attach an event listener to get all contacts
  contactsRef.on('value', function(snapshot) {
    var contacts = snapshot.val();
    console.log(contacts);
  });
  
  function getAllMessages(orderId) {
    var messagesRef = database.ref('chats/' + orderId + '/messages');
    
    // Attach a child_added event listener
    messagesRef.on('child_added', function(snapshot) {
      var message = snapshot.val();
      console.log('New message:', message);
    });
  }
  
  // Call the function with an order ID
  var orderId = '11200001001';
  getAllMessages(orderId);
  
  function fetchOrdersByClientId(clientId) {
    return new Promise(function(resolve, reject) {
      var orders = {};
  
      contactsRef.on('value', function(snapshot) {
        var ordersSnapshot = snapshot.val();
        for (var orderNumber in ordersSnapshot) {
          var orderData = ordersSnapshot[orderNumber];
          if (orderData.chat_master && orderData.chat_master.client_id === clientId) {
            orders[orderNumber] = orderData;
          }
        }
        resolve(orders);
      }, function(error) {
        reject(error);
      });
    });
  }
  
  // Call the function with a client ID
  var clientId = '5';
  function main() {
    fetchOrdersByClientId(clientId).then(function(orders) {
      console.log('Orders for client:', orders);
    }).catch(function(error) {
      console.error('Error:', error);
    });
  }
  
  main();
  
  function insert() {
    alert("yes");
    var order_id = document.getElementById("order_id").value;
    var order_no = document.getElementById("order_no").value;
    var customer = document.getElementById("customer").value;
    var user_id = document.getElementById("user_id").value;
    var c_name = document.getElementById("c_name").value;
    var c_phone = document.getElementById("c_phone").value;
    var message = document.getElementById("message-to-send").value; // Assuming you are using input elements directly
  
    // Generate formatted date and random string
    var formattedDate = new Date().toISOString();
    var randomString = generateRandomString(10); // You can define the generateRandomString function as shown earlier
  
    // Construct the data object
    var data = {};
    data[order_no] = {
      chat_master: {
        rider_id: user_id,
        client_id: customer,
        created_at: formattedDate,
        image: "https://www.theportlandclinic.com/wp-content/uploads/2019/07/Person-Curtis_4x5-e1564616444404.jpg",
        last_seen: formattedDate,
        name: c_name,
        orderId: order_id,
        phone: c_phone
      },
      messages: {}
    };
    data[order_no].messages[randomString] = {
      created_at: formattedDate,
      master_id: order_id,
      message: message,
      sender: "1"
    };
  
    contactsRef.update(data).then(function() {
      console.log("New record has been inserted successfully");
    }).catch(function(error) {
      console.error("Error inserting new record:", error);
    });
  }
  
  function insertMessages(message, order_id) {
    var messagesRef = database.ref('chats/' + order_id + '/messages');
    messagesRef.update(message).then(function() {
      console.log("New record has been inserted successfully");
    }).catch(function(error) {
      console.error("Error inserting new record:", error);
    });
  }
  
  function generateRandomString(length) {
    var characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    var result = '';
    for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
  }
  
  var s = generateRandomString(5);
  
  var dataM = {};
  dataM[s] = {
    "created_at": new Date().toLocaleString(),
    "master_id": "123456",
    "message": "when you will arrive",
    "sender": "1"
  };
  
  insertMessages(dataM, '11200001012');
  // contactsRef.push(data, function () {
  //     console.log("data has been inserted");
  // });
</script>

		
		<script>
		
		const currentDate = new Date();

        const options = {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          timeZoneName: 'short',
        };
        
        const formattedDate = currentDate.toLocaleDateString('en-US', options);
        
        function str_shuffle(str) {
          return str.split('').sort(function() { return 0.5 - Math.random() }).join('');
        }
        
        function str_repeat(str, num) {
          return new Array(num + 1).join(str);
        }
        
        function generateRandomString(length) {
          const characters = '0123456789abcdefghijklmnopqrstuvwxyz';
          const shuffledCharacters = str_shuffle(characters);
          const repeatedShuffled = str_repeat(shuffledCharacters, Math.ceil(length / shuffledCharacters.length));
          
          return repeatedShuffled.substr(0, length);
        }
        
        const randomString = generateRandomString(5);

		
    // AJAX script to handle form submission
    $(document).ready(function() {
        
        var input = document.getElementById("message-to-send");
        input.addEventListener("keypress", function(event) {
          if (event.key === "Enter") {
            event.preventDefault();
            var x = document.getElementById('sendmass');
                  if (x.style.display === 'none') {
                    document.getElementById("sendMessage").click();
                  }else{
                      document.getElementById("sendmass").click();
                  }
            
            
          }
        });
            
        
        
        $("#start_chat").click(function() {
          
        	const order_id = document.getElementById("order_id").value;
        	const order_no = document.getElementById("order_no").value;
        	const customer = document.getElementById("customer").value;
        	const user_id = document.getElementById("user_id").value;
        	const rider_type = document.getElementById("rider_type").value;
        	const name = document.getElementById("name").value;
        	$.ajax({
                type: "POST",
                url: "pages/chat_with_rider/function.php",
                data: {
                    action: "check_chat",
                    order_no: order_no,
                },
                dataType: "json",
                success: function (data) {
                    if(data.master_id != ''){
                   document.getElementById('chat_master_id').value =data.master_id;
                   document.getElementById("name").innerHTML = name;
                        document.getElementById('order_no1').innerHTML = order_no;
                        document.getElementById('image').innerHTML = ' <img src="https://a.icargos.com/portal/admin/img/download.jpg " alt="avatar"> ';
                         $('#last_message').data('message-id','0');
                    loadMoreMessages();
                    loadList();
                    }else{
                        document.getElementById("name").innerHTML = name;
                        document.getElementById('order_no1').innerHTML = order_no;
                        document.getElementById('user').value =user_id;
                        document.getElementById('image').innerHTML = ' <img src="https://a.icargos.com/portal/admin/img/download.jpg " alt="avatar"> ';
                        var x = document.getElementById('sendmass');
                          if (x.style.display === 'none') {
                            x.style.display = 'block';
                          } 
                       var y = document.getElementById('sendMessage');
                          if (y.style.display === 'block') {
                            y.style.display = 'none';
                          } 
                                }
                }
                });
        	
        });
        
        
        
      $("#submitbutton").click(function() {
          
        	const order_number = document.getElementById("ordername").value;
        	const rider = document.getElementById("selectoption").value;
            
            $.ajax({
                type: "POST",
                url: "pages/chat_with_rider/function.php",
                data: {
                    action: "check",
                    order_number: order_number,
                    rider: rider,
                },
                dataType: "json",
                success: function (response) {
                    if (response.track_no == null) {
                        alert('Rider not found');
                        document.getElementById("ordernumber").innerHTML = response.track_no;
                        document.getElementById("rider").innerHTML = response.rider_name;
                         var x = document.getElementById('start_chat');
                        x.style.display = 'none';
                        
                    }else{
                        document.getElementById("ordernumber").innerHTML = response.track_no;
                        document.getElementById('order_id').value = response.order_id;
                        document.getElementById('order_no').value = response.track_no;
                        document.getElementById('customer').value = response.customer_id;
                        document.getElementById('user_id').value = response.rider;
                        document.getElementById('rider_type').value = response.rider_type;
                        document.getElementById("rider").innerHTML = response.rider_name;
                        document.getElementById('name').value = response.rider_name;
                        document.getElementById('c_name').value = response.name;
                        document.getElementById('c_phone').value = response.phone;
                        var x = document.getElementById('start_chat');
                        x.style.display = 'block';
                    }
                }
            });
      });
      
      
      
    });
     
    function start_chat(id){
        
        
        $.ajax({
        type: "POST",
        url: "pages/chat_with_rider/function.php",
        data: {
            action: "start",
            master_id: id,
        },
        dataType: "json",
        success: function (data) {
           document.getElementById("name").innerHTML = data.user.Name;
            document.getElementById('order_no1').innerHTML = data.master.order_number;
            document.getElementById('user').value =data.user.id;
            document.getElementById('chat_master_id').value =data.master.id;
            document.getElementById('image').innerHTML = data.image;
            $('#last_message').data('message-id','0');
            loadMoreMessages();
            
        }
        });
    }
   


function loadList() {
      
    $.ajax({
        url: 'pages/chat_with_rider/function.php',
        data: {
            action: "get_list",
        },
        type: 'GET',
        dataType: "json",
        success: function (data) {
            console.log(data);
            $('#list').html(data); // Update chat-box with new messages
        },
        error: function () {
            alert('Error loading messages.');
        }
    });
}
 $('.list li').click(function(){
        $('li').removeClass("active");
        $(this).addClass("active");
     });
function sendMessage() {
    var message = $('#message-to-send').val();
    var master_id = $('#chat_master_id').val();
    var user = $('#user').val();
    $.ajax({
        url: 'pages/chat_with_rider/function.php',
        type: 'POST',
        data: {
            action: "send",
            message: message ,
            master_id:master_id,
            user:user,
            
        },
        success: function () {
            $('#message-to-send').val(''); // Clear the input field
            $('#last_message').data('message-id','0');
            loadMoreMessages(); // Refresh chat-box with new messages
        },
        error: function () {
            alert('Error sending the message.');
        }
    });
}



// Use Axios to make the POST request
    // axios.post("https://c.a.icargos.com/api/insert", data, {
    //     headers: {
    //         "Content-Type": "application/json",
    //         "Access-Control-Allow-Origin": "*", // Change this to the allowed origin(s)
    //         "Access-Control-Allow-Methods": "POST",
    //         "Access-Control-Allow-Headers": "Content-Type, Authorization",
    //         "Access-Control-Allow-Credentials": true // If using credentials
    //     }
    // })
    // .then(response => {
    //     $('#message-to-send').val('');
    //     loadMoreMessages();
    //     loadList();
    // })
    // .catch(error => {
    //     console.error("Error:", error);
    // });
// function sendmass() {
//     const order_id = document.getElementById("order_id").value;
//     const order_no = document.getElementById("order_no").value;
//     const customer = document.getElementById("customer").value;
//     const user_id = document.getElementById("user_id").value;
//     const c_name = document.getElementById("c_name").value;
//     const c_phone = document.getElementById("c_phone").value;
//     const message = $('#message-to-send').val();

//     $.ajax({
//         type: "POST",
//         url: "pages/chat_with_rider/function.php", // Use the path to your PHP script
//         data: {
//              action: "curl",
//             order_id: order_id,
//             order_no: order_no,
//             customer: customer,
//             user_id: user_id,
//             c_name: c_name,
//             c_phone: c_phone,
//             message: message
//         },
//         dataType: "json",
//         success: function (data) {
//             $('#message-to-send').val('');
//             loadMoreMessages();
//             loadList();
//         }
//     });
// }




// $(document).ready(function() {
//     loadMoreMessages();
//     setInterval(loadMoreMessages, 3000); // Refresh chat every 3 seconds
// });
$(document).ready(function() {
   
    loadList();
});


function loadMoreMessages() {
         var isLoading = false;
        var master = $('#chat_master_id').val();
        if (!isLoading) {
            isLoading = true;
            var lastMessageID = $('#last_message').data('message-id');

            $("#loader").show();

            $.ajax({
                url: 'pages/chat_with_rider/function.php',
                data: {
                    action: "get_chat",
                    master_id: master,
                    last_message_id: lastMessageID // Pass the ID of the last displayed message
                },
                type: 'GET',
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#chat_box').html(data.mess);

                     $('#last_message').data('message-id', data.mess_id);
                    $("#loader").hide();
                    isLoading = false;
                    var chatBox = $('.chat-history');
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                },
                error: function() {
                    alert('Error loading messages.');
                    $("#loader").hide();
                    isLoading = false;
                }
            });
        }
    }

    // Initial load of messages
    loadMoreMessages();
function loadPreMessages() {
         var isLoading = false;
        var master = $('#chat_master_id').val();
        if (!isLoading) {
            isLoading = true;
            var lastMessageID = $('#last_message').data('message-id');

            $("#loader").show();

            $.ajax({
                url: 'pages/chat_with_rider/function.php',
                data: {
                    action: "pre_get_chat",
                    master_id: master,
                    last_message_id: lastMessageID // Pass the ID of the last displayed message
                },
                type: 'GET',
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#chat_box1').html(data.mess);

                     $('#last_message').data('message-id', data.mess_id);
                    $("#loader").hide();
                    isLoading = false;
                    
                },
                error: function() {
                    alert('Error loading messages.');
                    $("#loader").hide();
                    isLoading = false;
                }
            });
        }
    }
    // Load more messages when reaching the bottom of the page
    $('.chat-history').on('scroll', function() {
    if ($('.chat-history').scrollTop() === 0 ) {
        loadPreMessages();
       
           

    }
});
    
    
    
    function searchList() {
        var searchTerm = $('#search_input').val();

        if (searchTerm !== '') {
            $.ajax({
                url: 'pages/chat_with_rider/function.php',
                data: {
                    action: "get_search_list",
                    search_term: searchTerm
                },
                type: 'GET',
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#list').html(data);
                },
                error: function () {
                    alert('Error loading messages.');
                }
            });
        } else {
            // If the search input is empty, load the default list1
            loadList();
        }
    }

    $('#search_input').on('keyup', function(event) {
        // if (event.which === 11) {
            searchList();
        // }
    });

    
  </script>
  
  
    
    
        