# AsyncPack

This is a simple code pack for use with a Jetty/ActiveMQ/PHP server I have built.  You will find some information at (http://www.eschrade.com)[ESchrade] though I have not written up anything yet.  One of the features of that software is building an integrated worker queue that works out of the box.  This library is designed to work with that.

There are two components.  The client and the server.  The client is intended for use in your application when there is a job you want to have queued and executed asynchronously.  Using it is fairly simple.

```
// The class to be executed by the server

class MyJob
{
   protected $param;
   
   public function __construct($param) {
       $this->param = $param;
   }
   
   public function execute()
   {
      // Do something with $this->param
   }

}

// Then, in your client program:

// to be continued... Restarting Windows...
```
