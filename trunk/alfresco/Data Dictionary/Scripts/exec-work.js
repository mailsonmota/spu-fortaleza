var workflow = actions.create("start-workflow");
workflow.parameters.workflowName = "jbpm$spu:moveFiles"; 
workflow.execute(document);