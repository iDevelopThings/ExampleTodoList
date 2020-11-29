<template>


    <div class="flex justify-center py-10">

        <div class="flex flex-col space-y-4 w-full justify-center max-w-4xl ">


            <div class="mb-4 bg-gray-100 rounded shadow p-4 flex flex-col space-y-3 bg-gray-800">

                <input type="text" class="rounded" v-model="title" placeholder="Title of task">

                <textarea name="description"
                          id="description"
                          cols="10"
                          rows="3"
                          v-model="description"
                          placeholder="Whats up doc?"></textarea>

                <button @click="addTodo"
                        type="button"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">

                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                         xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         v-if="creating"
                         viewBox="0 0 24 24">
                        <circle class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75"
                              fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Add todo
                </button>

            </div>

            <div v-if="loading">
                Loading todos...
            </div>

            <div v-if="!loading && todos" class="space-y-6">

                <div v-for="(todo, index) in todos.data"
                     class="flex flex-row items-center bg-gray-800 rounded-md shadow p-6">
                    <div class="w-full">
                        <strong>{{todo.title}}</strong>

                        <p>{{todo.description}}</p>
                    </div>
                    <div>
                        <button type="button"
                                @click="toggleCompletion(todo)"
                                aria-pressed="false"
                                class="flex-shrink-0 group relative rounded-full inline-flex items-center justify-center h-5 w-10 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Toggle Todo</span>
                            <span aria-hidden="true"
                                  :class="{'bg-indigo-600' : todo.completed, 'bg-gray-200'  : !todo.completed}"
                                  class="absolute h-4 w-9 mx-auto rounded-full transition-colors ease-in-out duration-200"></span>
                            <span aria-hidden="true"
                                  :class="{'translate-x-5' : todo.completed, 'translate-x-0'  : !todo.completed}"
                                  class=" absolute left-0 inline-block h-5 w-5 border border-gray-200 rounded-full bg-white shadow transform ring-0 transition-transform ease-in-out duration-200"></span>
                        </button>

                        <button @click="deleteTodo(todo, index)"
                                type="button"
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">

                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 v-if="deleting"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75"
                                      fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Delete
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>


</template>

<script>
	export default {
		name    : "Todos",
		mounted()
		{
			this.getTodos();
		},
		data()
		{
			return {

				todos       : null,
				loading     : false,
				deleting    : false,
				title       : '',
				description : '',
				creating    : false,
			};
		},
		methods : {

			getTodos()
			{
				this.loading = true;

				axios.get('/api/tasks')
					.then(response => {
						this.todos = response.data;
					})
					.finally(() => this.loading = false);
			},

			toggleCompletion(todo)
			{
				axios.post(`/api/tasks/${todo.id}/complete`)
					.then(response => {
						todo.completed = response.data.data.completed;
					});
			},

			deleteTodo(todo, index)
			{
				if (this.deleting) return;

				this.deleting = true;

				axios.delete(`/api/tasks/${todo.id}`)
					.then(() => {
						this.$delete(this.todos.data, index);
					})
					.finally(() => this.deleting = false);
			},

			addTodo()
			{
				if (this.creating) return;

				this.creating = true;

				axios.post(`/api/tasks`, {
						title       : this.title,
						description : this.description
					})
					.then((response) => {
						this.title = '';
						this.description = '';

						this.todos.data.push(response.data.data);
					})
					.finally(() => this.creating = false);

			}

		}
	};
</script>

<style scoped lang="scss">

</style>
