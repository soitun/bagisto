<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.customers.groups.index.title')
    </x-slot:title>

    <v-create-group></v-create-group>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-group-template">
            <div>
                <div class="flex justify-between items-center">
                    <p class="text-[20px] text-gray-800 font-bold">
                        @lang('admin::app.customers.groups.index.title')
                    </p>
            
                    <div class="flex gap-x-[10px] items-center">
                        <div class="flex gap-x-[10px] items-center">
                            <!-- Create a new Group -->
                            @if (bouncer()->hasPermission('customers.groups.create'))
                                <button 
                                    type="button"
                                    class="primary-button"
                                    @click="id=0; $refs.groupCreateModal.open()"
                                >
                                    @lang('admin::app.customers.groups.index.create.create-btn')
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- DataGrid -->
                <x-admin::datagrid src="{{ route('admin.customers.groups.index') }}" ref="datagrid">
                    <!-- DataGrid Header -->
                    <template #header="{ columns, records, sortPage, applied}">
                        <div class="row grid grid-cols-4 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] border-gray-300 text-gray-600 bg-gray-50 font-semibold">
                            <div
                                class="flex gap-[10px] cursor-pointer"
                                v-for="(columnGroup, index) in ['id', 'code', 'name']"
                            >
                                <p class="text-gray-600">
                                    <span class="[&>*]:after:content-['_/_']">
                                        <span
                                            class="after:content-['/'] last:after:content-['']"
                                            :class="{
                                                'text-gray-800 font-medium': applied.sort.column == columnGroup,
                                                'cursor-pointer': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
                                            }"
                                            @click="
                                                columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === columnGroup)): {}
                                            "
                                        >
                                            @{{ columns.find(columnTemp => columnTemp.index === columnGroup)?.label }}
                                        </span>
                                    </span>

                                    <!-- Filter Arrow Icon -->
                                    <i
                                        class="ml-[5px] text-[16px] text-gray-800 align-text-bottom"
                                        :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                        v-if="columnGroup.includes(applied.sort.column)"
                                    ></i>
                                </p>
                            </div>
    
                            <!-- Actions -->
                            <p class="flex gap-[10px] justify-end">
                                @lang('admin::app.components.datagrid.table.actions')
                            </p>
                        </div>
                    </template>

                    <!-- DataGrid Body -->
                    <template #body="{ columns, records }">
                        <div
                            v-for="record in records"
                            class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600 transition-all hover:bg-gray-100"
                            style="grid-template-columns: repeat(4, 1fr);"
                        >
                            <!-- Id -->
                            <p v-text="record.id"></p>

                            <!-- Code -->
                            <p v-text="record.code"></p>

                            <!-- Name -->
                            <p v-text="record.name"></p>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                <a @click="id=1; editModal(record)">
                                    <span
                                        :class="record.actions['0'].icon"
                                        class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 max-sm:place-self-center"
                                        :title="record.actions['0'].title"
                                    >
                                    </span>
                                </a>

                                <a @click="deleteModal(record.actions['1']?.url)">
                                    <span
                                        :class="record.actions['1'].icon"
                                        class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 max-sm:place-self-center"
                                        :title="record.actions['1'].title"
                                    >
                                    </span>
                                </a>
                            </div>
                        </div>
                    </template>
                </x-admin::datagrid>

                <!-- Modal Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="modalForm"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Create Group Modal -->
                        <x-admin::modal ref="groupCreateModal">          
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 font-bold">
                                    <span v-if="id">
                                        @lang('admin::app.customers.groups.index.edit.title')
                                    </span>
                                    <span v-else>
                                        @lang('admin::app.customers.groups.index.create.title')
                                    </span>
                                        
                                </p>    
                            </x-slot:header>
            
                            <x-slot:content>
                                <!-- Modal Content -->
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.customers.groups.index.create.code')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                            id="id"
                                            :label="trans('admin::app.customers.groups.index.create.code')"
                                            :placeholder="trans('admin::app.customers.groups.index.create.code')"
                                        >
                                        </x-admin::form.control-group.control>
            

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="code"
                                            id="code"
                                            rules="required"
                                            :label="trans('admin::app.customers.groups.index.create.code')"
                                            :placeholder="trans('admin::app.customers.groups.index.create.code')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="code"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
            
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.customers.groups.index.create.name')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            id="last_name"
                                            rules="required"
                                            :label="trans('admin::app.customers.groups.index.create.name')"
                                            :placeholder="trans('admin::app.customers.groups.index.create.name')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="name"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>
            
                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-[10px] items-center">
                                    <button 
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('admin::app.customers.groups.index.create.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-group', {
                template: '#v-create-group-template',

                data() {
                    return {
                        id: 0,
                    }
                },

                methods: {
                    create(params, { resetForm, setErrors  }) {
                        if (params.id) {
                            this.$axios.post("{{ route('admin.customers.groups.update') }}", params)
                                .then((response) => {
                                    this.$refs.groupCreateModal.close();

                                    this.$refs.datagrid.get();

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                    resetForm();
                                })
                                .catch(error => {
                                    if (error.response.status ==422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        } else {
                            this.$axios.post("{{ route('admin.customers.groups.store') }}", params)
                            .then((response) => {
                                this.$refs.groupCreateModal.close();

                                this.$refs.datagrid.get();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                        }
                    },

                    editModal(value) {
                        this.$refs.groupCreateModal.toggle();

                        this.$refs.modalForm.setValues(value);
                    },

                    deleteModal(url) {
                        if (! confirm('Are you sure, you want to perform this action?')) {
                            return;
                        }

                        this.$axios.post(url, {
                            '_method': 'DELETE'
                        })
                            .then((response) => {
                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }
                }
            })
        </script>
    @endPushOnce

</x-admin::layouts>