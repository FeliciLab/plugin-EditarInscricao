(function (angular) {
    var module = angular.module('errorValidation', ['ngSanitize']);

    // modifica as requisições POST para que sejam lidas corretamente pelo Slim
    module.config(['$httpProvider', function ($httpProvider) {
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $httpProvider.defaults.transformRequest = function (data) {
            var result = angular.isObject(data) && String(data) !== '[object File]' ? $.param(data) : data;

            return result;
        };
    }]);

    module.factory('ValidationService', ['$http', '$rootScope', '$q', 'UrlService',
        function ($http, $rootScope, $q, UrlService) {
            var url = new UrlService('registration');
            var labels = MapasCulturais.gettext.moduleOpportunity;

            return {
                getUrl: function (action, registrationId) {
                    return url.create(action, registrationId);
                },

                send: function (registrationId) {
                    return $http.post(this.getUrl('send', registrationId)).
                        success(function (data, status) {
                            $rootScope.$emit('registration.send', { message: "Opportunity registration was send ", data: data, status: status });
                        }).
                        error(function (data, status) {
                            $rootScope.$emit('error', { message: "Cannot send opportunity registration", data: data, status: status });
                        });
                },

                save: function () {
                    //jQuery('a.js-submit-button').click();
                },
            };
    }]);

    // Controlador da interface
    module.controller('ValidationController', ['$scope', '$rootScope', '$location', '$anchorScroll',
        '$timeout', 'ValidationService', '$http', '$window', function ($scope, $rootScope, $location,
            $anchorScroll, $timeout, ValidationService, $http, $window) {

            $scope.data = {};
            $scope.data.propLabels = [];

            for (var name in MapasCulturais.labels.agent) {
                var label = MapasCulturais.labels.agent[name];
                $scope.data.propLabels.push({
                    name: name,
                    label: label
                });
            }

            $scope.data.entity = MapasCulturais.entity;
            $scope.sendRegistration = function (redirectUrl) {
                ValidationService.send($scope.data.entity.id).success(function (response) {
                    $('.js-response-error').remove();
                    if (response.error) {
                        var focused = false;
                        Object.keys(response.data).forEach(function (field, index) {
                            var $el;
                            if (field === 'projectName') {
                                $el = $('#projectName').parent().find('.label');
                            } else if (field === 'category') {
                                $el = $('#category').parent().find('.attachment-description');
                            } else if (field.indexOf('agent') !== -1) {
                                $el = $('#' + field).parent().find('.registration-label');
                            } else if (field.indexOf('space') !== -1) {
                                $el = $('#registration-space-title').parent().find('.registration-label');
                            } else {
                                $el = $('#' + field).find('div:first');
                            }
                            //para adicionar o nome dos campos
                            var nameFields = [];
                            //loop para preencher o array com o nome de cada campo
                            $.each($el, function (indexInArray, val) { 
                                nameFields.push(val.innerText);
                            });
                            $.each(nameFields, function (index, valor) { 
                                //adicionando cada nome do array em uma LI
                                $("#info-erros-required-fields").append('<li>'+valor+'</li>'); 
                            });
                            //chamando o moda
                            var modal = $('[data-remodal-id=remodal_info_field_required]').remodal();
                            modal.open();
                            
                        });
                        //MapasCulturais.Messages.error('Error Validation');
                    } else {
                        $scope.data.sent = true;
                        MapasCulturais.Messages.success(labels['registrationSent']);

                        if (redirectUrl) {
                            document.location = redirectUrl;
                        }
                        else if (redirectUrl === undefined) {
                            document.location = response.redirect || response.singleUrl;
                        }
                    }
                });

                $('[data-remodal-id=modal-info-registration-confirm]').remodal().close();
            };


    }]);

    module.factory('RegistrationService', ['$http', '$rootScope', '$q', 'UrlService', function ($http, $rootScope, $q, UrlService) {
        var url = new UrlService('registration');
        var labels = MapasCulturais.gettext.moduleOpportunity;
    
        return {
            getUrl: function(action, registrationId){
                return url.create(action, registrationId);
            },
    
            register: function (params, idOpportunity) {
               
                var data = {
                    opportunityId: idOpportunity,
                    ownerId: params.owner.id,
                    category: params.category
                };
    
                return $http.post( this.getUrl(), data).
                success(function (data, status) {
                    $rootScope.$emit('registration.create', {message: "Opportunity registration was created", data: data, status: status});
                }).
                error(function (data, status) {
                    $rootScope.$emit('error', {message: "Cannot create opportunity registration", data: data, status: status});
                });
            },
    
            setMultipleStatus: function(registrations) {
                var endPoint = url.create('setMultipleStatus');
    
                return $http.post(endPoint, { evaluations: registrations }).
                success(function (data) {
                    for(var aval in data) {
                        var slug = _getStatusSlug(data[aval]);
                        $("#registration-" +  aval).attr('class', slug);
    
                        var txt = $("#registration-" +  aval + " .registration-status-col").first().text();
                        $("#registration-" + aval + " .registration-status-col .dropdown.js-dropdown div").text(txt);
                    }
                });
            },
    
            setStatusTo: function(registration, registrationStatus){
                return $http.post(this.getUrl('setStatusTo', registration.id), {status: registrationStatus}).
                success(function (data, status) {
                    registration.status = data.status;
                    $rootScope.$emit('registration.' + registrationStatus, {message: "Opportunity registration status was setted to " + registrationStatus, data: data, status: status});
                }).
                error(function (data, status) {
                    $rootScope.$emit('error', {message: "Cannot " + registrationStatus + " opportunity registration", data: data, status: status});
                });
    
            },
    
            verifyMinimumNote: function(opportunity) {
                var dataOpp = {
                    id: opportunity
                }
                return $http.post(MapasCulturais.baseURL+'opportunity/minimumNote', dataOpp);
            },
    
            send: function(registrationId){
                return $http.post(this.getUrl('send', registrationId)).
                success(function(data, status){
                    $rootScope.$emit('registration.send', {message: "Opportunity registration was send ", data: data, status: status});
                }).
                error(function(data, status){
                    $rootScope.$emit('error', {message: "Cannot send opportunity registration", data: data, status: status});
                });
            },
    
            save: function () {
                //jQuery('a.js-submit-button').click();
            },
    
            validateEntity: function(registrationId) {
                return $http.post(this.getUrl('validateEntity', registrationId)).
                success(function(data, status){
                    $rootScope.$emit('registration.validate', {message: "Opportunity registration was validated ", data: data, status: status});
                }).
                error(function(data, status){
                    $rootScope.$emit('error', {message: "Cannot validate opportunity registration", data: data, status: status});
                });
            }, 
    
            updateFields: function(entity) {
                var data = {};
    
                Object.keys(entity).forEach(function(key) {
                    // para excluir propriedades do angular
                    if(key.indexOf('$$') == -1){
                        data[key] = entity[key];
    
                        if (data[key] instanceof Date) {
                            data[key] = moment(data[key]).format('YYYY-MM-DD')
                        } else if (data[key] instanceof Array && data[key].length === 0) {
                            data[key] = null;
                        }
                    }
                });
    
                return $http.patch(this.getUrl('single', entity.id), data).
                    success(function(data, status){
                        MapasCulturais.Messages.success(labels['changesSaved']);
                        $rootScope.$emit('registration.update', {message: "Opportunity registration was updated ", data: data, status: status});
                    }).
                    error(function(data, status){
                        
                        $rootScope.$emit('error', {message: "Cannot update opportunity registration", data: data, status: status});
                    });
            },
    
            getFields: function () {
                var fieldTypes = MapasCulturais.registrationFieldTypes.slice();
    
                var fieldTypesBySlug = {};
    
                fieldTypes.forEach(function (e) {
                    fieldTypesBySlug[e.slug] = e;
                });
    
                function processFieldConfiguration(field) {
                    if(typeof field.fieldOptions === 'string'){
                        field.fieldOptions = field.fieldOptions ? field.fieldOptions.split("\n") : [];
                    }
    
                    if(typeof field.config !== 'object' || field.config instanceof Array) {
                        field.config = {};
                    }
    
                    return field;
                }
    
                function processFileConfiguration(file) {
                    file.fieldType = 'file';
                    file.categories = file.categories || [];
                    return file;
                }
    
                var _files = MapasCulturais.entity.registrationFileConfigurations.map(processFileConfiguration);
                var _fields = MapasCulturais.entity.registrationFieldConfigurations.map(processFieldConfiguration);
    
    
                var fields = _files.concat(_fields);
    
                fields.sort(function(a,b){
                    if(a.displayOrder > b.displayOrder){
                        return 1;
                    } else if(a.displayOrder < b.displayOrder){
                        return -1;
                    }else {
                        return 0;
                    }
                });
    
                return fields;
            },
    
            getSelectedCategory: function(){
                
                return $q(function(resolve){
                    var interval = setTimeout(function(){
                        var $editable = jQuery('.js-editable-registrationCategory');
    
                        if($editable.length){
                            var editable = $editable.data('editable');
                            if(editable){
                                clearInterval(interval);
                                resolve(editable.value);
                            }
                        }else{
                            resolve(MapasCulturais.entity.object.category);
                        }
                    },50)
                });
            },
    
            registrationStatuses: [
                {value: "", label: labels['allStatus']},
                {value: 1, label: labels['pending']},
                {value: 2, label: labels['invalid']},
                {value: 3, label: labels['notSelected']},
                {value: 8, label: labels['suplente']},
                {value: 10, label: labels['selected']},
                {value: 0, label: labels['draft']}
            ],
    
            registrationStatusesNames: [
                {value: 1, label: labels['pending']},
                {value: 2, label: labels['invalid']},
                {value: 3, label: labels['notSelected']},
                {value: 8, label: labels['suplente']},
                {value: 10, label: labels['selected']},
                {value: 0, label: labels['draft']}
            ],
    
            publishedRegistrationStatuses: [
                {value: null, label: labels['allStatus']},
                {value: 8, label: labels['suplente']},
                {value: 10, label: labels['selected']}
            ],
    
            publishedRegistrationStatusesNames: [
                {value: 8, label: labels['suplente']},
                {value: 10, label: labels['selected']}
            ],
    
            setStatusCandidate: function() {
                var dataStatus = {opportunity: MapasCulturais.entity.id}
                return $http.post(MapasCulturais.baseURL+'opportunity/setStatus', dataStatus).
                success(function(data, status){
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Sucesso!',
                        text: data.message,
                        type: 'success',
                        icon: 'fa fa-check',
                        shadow: true
                    }); 
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }).
                error(function(error, status){
                    new PNotify({
                        title: 'Erro!',
                        text: 'Ocorreu um erro inesperado',
                        type: 'error',
                        icon: 'fa fa-exclamation-circle',
                        shadow: true
                    }); 
                });
            }
        };
    }]);

    module.controller('RegistrationFieldsController', ['$scope', '$timeout', 'RegistrationService', 'EditBox', '$http', 'UrlService', function ($scope, $timeout, RegistrationService, EditBox, $http, UrlService) {
        var registrationsUrl = new UrlService('registration');
    
        var labels = MapasCulturais.gettext.moduleOpportunity;
    
        $scope.uploadUrl = registrationsUrl.create('upload', MapasCulturais.entity.id);
    
        $scope.maxUploadSizeFormatted = MapasCulturais.maxUploadSizeFormatted;
    
        $scope.entity = MapasCulturais.entity.object;
    
        // $scope.entityErrors = {};
    
        $scope.data = {
            fileConfigurations: MapasCulturais.entity.registrationFileConfigurations
        };
    
        $scope.data.birthDay = "";
    
        $timeout(function(){
            $scope.ibge = MapasCulturais.ibge;
        }, 200)
    
        $scope.data.fileConfigurations.forEach(function(item){
            item.file = MapasCulturais.entity.registrationFiles[item.groupName];
        });
        
        $scope.data.fields = RegistrationService.getFields();
        $scope.data.fieldsRequiredLabel = labels['requiredLabel'];
        $scope.data.fieldsOptionalLabel = labels['optionalLabel'];
    
        $scope.data.fields.forEach(function(field) {
            var val = $scope.entity[field.fieldName];
    
            field.unchangedFieldJSON = JSON.stringify(val);        
    
            if (field.fieldType == 'date' && typeof val == 'string' ) {
                val = moment(val).toDate();
            } else if(field.fieldType == 'number' && typeof val == 'string' ) {
                val = parseFloat(val);
            } else if (/\d{4}-\d{2}-\d{2}/.test(val)) {
                val = moment(val).toDate();
            }
    
            $scope.entity[field.fieldName] = val;
        });
    
        var timeouts = {};
    
        $scope.saveField = function (field, value, delay) {
            if (field.unchangedFieldJSON == JSON.stringify(value)) {
                return;
            }
            
            delete field.error;
            $timeout.cancel(timeouts['entity_' + field.fieldName]);
            
            timeouts['entity_' + field.fieldName] = $timeout(function(){
                field.unchangedFieldJSON = JSON.stringify(value);
    
                var data = {
                    id: MapasCulturais.entity.object.id
                };
    
                data[field.fieldName] = value;
                RegistrationService.updateFields(data)
                    .success(function(){
                        $scope.removeFieldErrors(field.fieldName);
                        delete field.error;
                    })
                    .error(function(r) {
                        if (Array.isArray(Object.values(r.data)) && Object.values(r.data).lenght != 0 ){
                            var fields = MapasCulturais.entity.registrationFieldConfigurations;
                            var nameField = '';
                            fields.forEach(element => {
                                if(element.fieldName == field.fieldName) {
                                    nameField = element.title;
                                }
                            });
                            field.error = [Object.values(r.data).join(', ')];
                            //$scope.entityErrors[field.fieldName] = field.error;
                            var errors = field.error;
                            var msgError = '<span> ' + nameField + '. </span> (<small>'+errors+'</small>)';
                            $("#title_modal_required_info").text('');
                            $("#title_modal_required_info").text('Verifique o formato dos campos solicitados.');
                            $("#info-erros-required-fields").append('<li>'+msgError+'</li>');
                            $("#subTitle_modal_required_info").text("Para prosseguir com sua inscrição, é necessário que você forneça os dados no formato solicitado nos campos");
                            $("#infoField_modal_required_info").text("Você precisa verificar o formato dos seguintes campos:");
                            var modal = $('[data-remodal-id=remodal_info_field_required]').remodal();
                            modal.open();
                        }
                    });
            },delay);
        }
    
        $scope.removeFieldErrors = function(fieldName) {
            if($scope.entityErrors) {
                delete $scope.entityErrors[fieldName];
                if(!$scope.$$phase) {
                    $scope.$apply();
                }
            }
        }
    
        $scope.numFieldErrors = function() {0

            if(typeof $scope.entityErrors == 'object') {
                return Object.keys($scope.entityErrors).length;
            } else {
                return 0;
            }
        }
    
        $scope.remove = function(array, index){
            array.splice(index, 1);
        }
    
    
        $scope.data.fields.forEach(function(field) {
            $scope.$watch('entity.' + field.fieldName, function(current, old){
                if(current == old){
                    return;
                }
    
                $scope.saveField(field, current, 90000)
            }, true);
        });
    
        function initMasks() {
            $('[js-mask]').each(function() {
                var $this = jQuery(this);
    
                if (!$this.data('js-mask-init')) {
                    $this.mask($this.attr('js-mask'));
                    $this.data('js-mask-init', true);
                }
            });
        }
        setInterval(initMasks, 1000);
        
    
        var fieldsByName = {};
    
        $scope.data.fields.forEach(function(e){
            fieldsByName[e.fieldName] = e;
        });
    
        $scope.sendFile = function(attrs){
            $('.carregando-arquivo').show();
            $('.submit-attach-opportunity').hide();
    
            var $form = $('#' + attrs.id + ' form');
            $form.submit();
            if(!$form.data('onSuccess')){
                $form.data('onSuccess', true);
                $form.on('ajaxForm.success', function(){
                    MapasCulturais.Messages.success(labels['changesSaved']);
                    var fieldName = $form.parents('.attachment-list-item').data('fieldName');
                    if(fieldName){
                        $scope.removeFieldErrors(fieldName);
                    } 
                });
            }
        };
    
        $scope.openFileEditBox = function(id, index, event){
            EditBox.open('editbox-file-'+id, event);
            initAjaxUploader(id, index);
        };
    
        $scope.removeFile = function (file) {
            if(confirm(labels['confirmRemoveAttachment'])){
                $http.get(file.deleteUrl).success(function(response){
                    for (var key in $scope.data.fields) {
                        var field = $scope.data.fields[key];
                        if (field.multiple && field.file instanceof Array) {
                            for (var f in field.file) {
                                var fil = field.file[f];
                                if (file.id == fil.id) {
                                    delete $scope.data.fields[key].file[f];
                                }
                            }
                        } else {
                            fil = field.file;
                            if (typeof fil !== 'undefined') {
                                if (file.id == fil.id) {
                                    delete $scope.data.fields[key].file;
                                }
                            }  
                        }
                    }
            
                    $("#" + file.id).remove();
                                 
                });
            }
        };
    
        var initAjaxUploader = function(id, index) {
            var $form = $('#editbox-file-' + id + ' form');
            if($form.data('initialized'))
                return;
            MapasCulturais.AjaxUploader.init($form);
    
            $('#editbox-file-'+id).on('cancel', function(){
                if($form.data('xhr')) $form.data('xhr').abort();
                $form.get(0).reset();
                MapasCulturais.AjaxUploader.resetProgressBar($form);
            });
    
            $form.on('ajaxForm.success', function(evt, response){
                var file = response[$scope.data.fields[index].groupName];
    
                if ($scope.data.fields[index].multiple) {
                    if ( typeof $scope.data.fields[index].file === 'undefined') {
                        $scope.data.fields[index].file = [file[0]];
                    } else {
                        $scope.data.fields[index].file.push(file[0]);
                    }
                    
                } else {
                    $scope.data.fields[index].file = file;
                }
                
                $scope.$apply();
                setTimeout(function(){
                    $('.carregando-arquivo').hide();
                    $('.submit-attach-opportunity').show();
                    EditBox.close('editbox-file-'+id, evt);
                }, 700);
            });
        };
    
        $scope.useCategories = MapasCulturais.entity.registrationCategories.length > 0;
    
    
        if($scope.useCategories){
            $scope.registrationCategories = MapasCulturais.entity.registrationCategories;
    
            RegistrationService.getSelectedCategory().then(function(value){
                $scope.selectedCategory = value;
            });
    
            $('.js-editable-registrationCategory').on('save', function(){
                RegistrationService.getSelectedCategory().then(function(value){
                    $scope.selectedCategory = value;
                });
            });
    
        }
    
        $scope.showFieldConfiguration = function (field) {
            if(field.categories.length === 0) {
                return true;
            }
    
            if(!$scope.data.filterFieldConfigurationByCategory) {
                return true;
            }
    
            if($scope.data.categories.length === 1) {
                return true;
            }
    
            if(!field.categories.includes($scope.data.filterFieldConfigurationByCategory)){
                console.log($scope.data.filterFieldConfigurationByCategory, field.categories);
            }
            return field.categories.includes($scope.data.filterFieldConfigurationByCategory);
        };
    
        setInterval(function () {
            RegistrationService.getSelectedCategory().then(function(value){
                $scope.selectedCategory = value;
            });
        }, 1000);
    
        $scope.showField = function(field){
    
            var result;
            if (!$scope.useCategories){
                result = true;
            } else {
                result = field.categories.length === 0 || field.categories.indexOf($scope.selectedCategory) >= 0;
            }
    
            if (field.config && field.config.require && field.config.require.condition && field.config.require.hide) {
                var requiredFieldName = field.config.require.field;
                var requeredFieldValue = field.config.require.value;
    
                result = result && $scope.entity[requiredFieldName] == requeredFieldValue;
            }

            return result;
        };
    
        
        $scope.requiredField = function(field) {
            if(field.required) {
                return 1;
            }
            
            if(field.config && field.config.require){
                var requiredFieldName = field.config.require.field;
                var requeredFieldValue = field.config.require.value;
        
                if(field.config.require && field.config.require.condition && $scope.entity[requiredFieldName] == requeredFieldValue){
                    return 2;
                }
            }
    
            return false;
        }
    
        $scope.printField = function(field, value){
            if (field.fieldType === 'date') {
                return moment(value).format('DD/MM/YYYY');
            } else if (field.fieldType === 'url'){
                return '<a href="' + value + '" target="_blank" rel="noopener noreferrer">' + value + '</a>';
            } else if (field.fieldType === 'email'){
                return '<a href="mailto:' + value + '"  target="_blank" rel="noopener noreferrer">' + value + '</a>';
            }  else if (field.fieldType === 'agent-owner-field' && typeof value ==='object' || field.fieldType === 'agent-collective-field' && typeof value ==='object'){
                // FORMATANDO A DATA DE NASCIMENTO
                return moment(value).format('DD/MM/YYYY');
            }else if (value instanceof Array) {
                return value.join(', ');
            } else {
                return value;
            }
        };
    }]);

})(angular);

$(function () {
    // LIMPANDO O ITENS
    $(document).on('closed', '.remodal', function (e) {
        $("#info-erros-required-fields").empty();
        //enviar requisição para rota opportunity.emptySession
        
    });

});