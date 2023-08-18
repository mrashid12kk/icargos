<!DOCTYPE html>
<html>
<head> 
    <title>Courier Shipping APP - IT Vision</title>
<style>
input[type=text], select {
  width: 50%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;

}

input[type=submit] {
  width: 50%;
  background-color: #071869;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 900;
}

input[type=submit]:hover {
  background-color: #071869c7;
}
.heading{
    font-family: sans-serif;
}

div {
  border-radius: 5px;
 
  margin-left: 30%;
  margin-top: 10%;
  
}
</style>
</head>
<body>



<div>
  <form action="/install.php?shop=" method="get" >
    <label for="url-name" class="heading">Enter Shop URL</label> <br>
    <br><input type="text" id="shop_name" name="shop" placeholder="demo.myshopify.com">
    <br>
    <br><input type="submit" value="Submit">
  </form>
</div>

</body>
</html>
