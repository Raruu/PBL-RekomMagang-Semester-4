 <table class="table table-sm table-bordered">
     <thead>
         <tr>
             <th scope="col">Semester</th>
             <th scope="col">IPK</th>
         </tr>
     </thead>
     <tbody>
         ${data.map((item, index) => `
         <tr>
             <td>${item.semester}</td>
             <td>${item.ipk}</td>
         </tr>
         `).join('')}
     </tbody>
 </table>
