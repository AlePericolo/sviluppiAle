import 'rxjs/add/operator/map'

let number: number = 2;
console.log(number);

const a = `Hello Peril my age is ${20 + 8}`;//risultato 44
console.log(a);

/*
let Array = ['file1.jpg', 'file2.png', 'file3.png'];
let ArrayNew = Array.map((el) => {
  //console.log(el);  
  return 'http://www.miosito.com/' + el;
})
console.log(ArrayNew);
*/

var arr = Rx.Observable.interval(1000);
arr.subscribe(function(num) {
  console.log('Elemento Osservato ' + num);
});