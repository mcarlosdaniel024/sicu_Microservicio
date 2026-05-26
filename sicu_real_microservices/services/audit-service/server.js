
const express = require('express');
const app = express();

app.get('/', (req,res)=>{
  res.json({
    service:'Audit Service',
    status:'running'
  });
});

app.listen(3000, ()=>{
  console.log('Audit service running on port 3000');
});
