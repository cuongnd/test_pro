package de.codecrafters.tableview;

import android.content.Context;
import android.content.res.Resources;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;


/**
 * The abstract implementation of an adapter used to bring data to a {@link TableHeaderView}.
 *
 * @author ISchwarz
 */
public abstract class TableHeaderAdapter {

    private TableColumnModel columnModel;
    private final Context context;


    /**
     * Creates a new TableHeaderAdapter.
     *
     * @param context
     *         The context that shall be used.
     */
    public TableHeaderAdapter(final Context context) {
        this(context, 0);
    }

    /**
     * Creates a new TableHeaderAdapter. (internally used)
     *
     * @param context
     *         The context that shall be used.
     * @param columnCount
     *         The number of columns.
     */
    protected TableHeaderAdapter(final Context context, final int columnCount) {
        this(context, new TableColumnModel(columnCount));
    }

    /**
     * Creates a new TableHeaderAdapter. (internally used)
     *
     * @param context
     *         The context that shall be used.
     * @param columnModel
     *         The column model to be used.
     */
    protected TableHeaderAdapter(final Context context, final TableColumnModel columnModel) {
        this.context = context;
        this.columnModel = columnModel;
    }

    /**
     * Gives the {@link Context} of this adapter. (Hint: use this method in the {@code getHeaderView()}-method
     * to programmatically initialize new views.)
     *
     * @return The {@link Context} of this adapter.
     */
    public Context getContext() {
        return context;
    }

    /**
     * Gives the {@link LayoutInflater} of this adapter. (Hint: use this method in the
     * {@code getHeaderView()}-method to inflate xml-layout-files.)
     *
     * @return The {@link LayoutInflater} of this adapter.
     */
    public LayoutInflater getLayoutInflater() {
        return (LayoutInflater) getContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    /**
     * Gives the {@link Resources} of this adapter. (Hint: use this method in the
     * {@code getHeaderView()}-method to resolve resources.)
     *
     * @return The {@link Resources} of the context of this adapter.
     */
    public Resources getResources() {
        return getContext().getResources();
    }

    /**
     * Sets the {@link TableColumnModel} that will be used to render the table headers.
     *
     * @param columnModel
     *         The {@link TableColumnModel} that should be set.
     */
    protected void setColumnModel(final TableColumnModel columnModel) {
        this.columnModel = columnModel;
    }

    /**
     * Gives the {@link TableColumnModel} that is currently used to render the table headers.
     */
    protected TableColumnModel getColumnModel() {
        return columnModel;
    }

    /**
     * Sets the column count which is used to render the table headers.
     *
     * @param columnCount
     *         The column count that should be set.
     */
    protected void setColumnCount(final int columnCount) {
        columnModel.setColumnCount(columnCount);
    }

    /**
     * Gives the column count that is currently used to render the table headers.
     *
     * @return The number of columns.
     */
    protected int getColumnCount() {
        return columnModel.getColumnCount();
    }

    /**
     * Sets the column weight (the relative width of a column) of the column at the given index.
     *
     * @param columnIndex
     *         The index of the column to which this weight should be assigned.
     * @param columnWeight
     *         The weight that should be set to the column at the given index.
     */
    protected void setColumnWeight(final int columnIndex, final int columnWeight) {
        columnModel.setColumnWeight(columnIndex, columnWeight);
    }

    /**
     * Gives the column weight (the relative width of a column) of the column at the given index.
     *
     * @param columnIndex
     *         The index of the column to receive the column weight.
     * @return The column weight of the column at the given index.
     */
    protected int getColumnWeight(final int columnIndex) {
        return columnModel.getColumnWeight(columnIndex);
    }

    /**
     * Gives the overall column weight (sum of all column weights).
     *
     * @return The collumn weight sum.
     */
    protected int getColumnWeightSum() {
        return columnModel.getColumnWeightSum();
    }

    /**
     * Method that gives the header views for the different columns.
     *
     * @param columnIndex
     *         The index of the column to return the header view.
     * @param parentView
     *         The view to which the returned view will be added.
     * @return The created header view for the given column.
     */
    public abstract View getHeaderView(int columnIndex, ViewGroup parentView);

}
