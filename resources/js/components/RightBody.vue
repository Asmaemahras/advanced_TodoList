<template>
  <div id="right">

    <h1> TODO LIST </h1>
    <div class="horizontal">
        <img src="../images/horizontal.png" />
    </div>
    <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
    <div class="tasks">
        <div class="add-tasks">
            <h2>Today's Task</h2>
            <div class="add-action">
                <img src="../images/add.png" />
            </div>
        </div>
        <ul class="tasks-list">
         <li v-for="task in todaytasks" v-bind:key="task.id">
            <div class="info">
              <div class="left">
                <label class="myCheckbox">
                  <input type="checkbox" name="test" 
                  :checked="task.completed"
                  @change="updateTodayTask(task.taskId)" />
                  <span></span>
                </label>
                <h4>{{ task.title }}</h4>
              </div>
              <div class="right">
                <img src="../images/edit.png" alt="" />
                <img src="../images/del.png"
                alt=""
                @click="deleteTask(task.taskId)" />
                <button
                v-bind:class="{
                  inprogress: !task.approved,
                  approved: task.approved,
                }"
              >
                {{ task.approved ? "Approved" : "In progress" }}
              </button> 
              </div>
            </div>
          </li>
        </ul>
    </div>
    <div class="upcoming">
      <div class="add-tasks">
        <h2>À Venir</h2>
        <div class="add-action">
          <img src="../images/add.png" alt="" />
        </div>
      </div>
        <form action="" @submit="addUpcomingTask">
          <input type="text" v-model="newTask" />
        </form>

        <ul class="tasks-list">
          <li v-for="upcomingtask in upcoming" v-bind:key="upcomingtask.id">
            <div class="info">
              <div class="left">
                <label class="myCheckbox">
                  <input type="checkbox" name="test" 
                  :checked="upcomingtask.completed"
                  @change="checkUpcoming(upcomingtask.taskId)" />
                  <span></span>
                </label>
                <h4>{{ upcomingtask.title }}</h4>
              </div>
              <div class="right">
                <img src="../images/edit.png" alt="" />
                <img src="../images/del.png"
                alt=""
                @click="delUpcoming(upcomingtask.taskId)" />
                
                <button
                v-bind:class="{
                  inprogress: !upcoming.waiting,
                  
                }"
              >
                Waiting
              </button> 
              </div>
            </div>
          </li>
        </ul>

      </div>
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            todaytasks: [],
            upcoming: [],
            newTask: "",
        };
    },
    created() {
        this.fetchTodayTasks();
        this.fetchUpcoming();
    },
    
    methods: {
        //upcoming task
        // get upcoming tasks
        fetchUpcoming() {
            fetch("/api/upcoming")
                .then((res) => res.json())
                .then(({ data }) => {
                    this.upcoming = data;
                    //console.log(data);
                })
                .catch((err) => console.log(err));
        },
        // ADD Upcoming task
        addUpcomingTask(e) {
          e.preventDefault();
          
         if(this.upcoming.length > 4) {
           alert("Complétez votre tâche à venir");
         }
         else {
           const newTask = {
             title: this.newTask,
             waiting: true,
             taskId: Math.random().toString(36).substring(7),
           };

           // post
           fetch("/api/upcoming", {
             method: "POST",
             headers: {
               "content-type": "application/json",
             },
             body: JSON.stringify(newTask),
           }).then(() => this.upcoming.push(newTask));
           this.newTask = "";
         }
        },

        //delete
        delUpcoming (taskId){
          if(confirm("Are you sure ?")){
            fetch("/api/upcoming/" +taskId, {
              method: "delete",
            })
            .then((res) => res.json())
            .then(() => {
              this.upcoming = this.upcoming.filter(
                ({ taskId: id }) => id !== taskId
              );
            })
            .catch((err) => console.log(err));
          }

          //check Upcoming
        },
                  
          checkUpcoming(taskId){
            if(this.todaytasks.length > 4){
              alert("désolé !");
              window.location.href="/";
            }
            else {
              this.addDailytask(taskId);
              console.log('check');
              fetch('/api/upcoming/{$taskId}', {method: "delete"}).then(
                () =>
                (this.upcoming = this.upcoming.filter(
                  ({ taskId: id }) => id !== taskId
                ))
              );
            }
          },

        // Today Task method
        fetchTodayTasks() {
          fetch("/api/dailytask")
          .then((res) => res.json())
          .then(({ data }) => {
            this.todaytasks = data;
           // console.log(data);
            })
          .catch((err) => console.log(err));
        },
        // ajouter une tâche

        addDailytask(taskId) {
          // get task
          const task = this.upcoming.filter(({ taskId: id }) => id == taskId)[0];
          console.log(task, 'task');
          //Post request
          fetch("/api/dailytask", {
            method: "POST",
            header: {
              "content-type": "application/json",
            },
            body: JSON.stringify(task),
          }).then(() => this.todaytasks.unshift(task))
          .catch(err => console.log(err));
        },

        // update today task
        updateTodayTask(taskId) {
          if(confirm("Votre tâche est complète ?")){
            fetch("/api/dailytask/" +taskId, { method: "delete" })
            .then(() => {})
            .then(() => {
              this.todaytasks = this.todaytasks.filter(
                ({ taskId: id }) => id !==taskId
              );
            })
            .catch((err) => console.log(err));
          }
        },

        // delete task
        deleteTask(taskId) {
          if(confirm("Are you sure?")) {
            fetch("/api/dailytask/" +taskId, {
              method: "delete",
            })
            .then((res) => res.json())
            .then(() => (this.todaytasks = this.todaytasks.filter(
              ({ taskId: id }) => id !== taskId
            ))
            ).catch((err) => console.log(err));
          }
        }, 
    },
};

</script>

<style>

</style>