var workflow = actions.create("start-workflow");
workflow.parameters.workflowName = "jbpm$spu:rascunhoComplete"; 
workflow.execute(document);

