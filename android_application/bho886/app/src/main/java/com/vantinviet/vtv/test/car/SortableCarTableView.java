package com.vantinviet.vtv.test.car;

import android.content.Context;
import android.util.AttributeSet;

import com.vantinviet.vtv.R;
import com.vantinviet.vtv.test.car.data.Car;

import de.codecrafters.tableview.SortableTableView;
import de.codecrafters.tableview.toolkit.SimpleTableHeaderAdapter;
import de.codecrafters.tableview.toolkit.SortStateViewProviders;
import de.codecrafters.tableview.toolkit.TableDataRowColorizers;


public class SortableCarTableView extends SortableTableView<Car> {


    public SortableCarTableView(Context context) {
        this(context, null);
    }

    public SortableCarTableView(Context context, AttributeSet attributes) {
        this(context, attributes, 0);
    }

    public SortableCarTableView(Context context, AttributeSet attributes, int styleAttributes) {
        super(context, attributes, styleAttributes);


        SimpleTableHeaderAdapter simpleTableHeaderAdapter = new SimpleTableHeaderAdapter(context, "Hersteller", "Bezeichung", "Leistung", "Preis");
        simpleTableHeaderAdapter.setTextColor(context.getResources().getColor(R.color.table_header_text));
        setHeaderAdapter(simpleTableHeaderAdapter);

        int rowColorEven = context.getResources().getColor(R.color.table_data_row_even);
        int rowColorOdd = context.getResources().getColor(R.color.table_data_row_odd);
        setDataRowColoriser(TableDataRowColorizers.alternatingRows(rowColorEven, rowColorOdd));
        setHeaderSortStateViewProvider(SortStateViewProviders.brightArrows());

        setColumnWeight(0, 2);
        setColumnWeight(1, 3);
        setColumnWeight(2, 3);
        setColumnWeight(3, 2);

        setColumnComparator(0, CarComparators.getCarProducerComparator());
        setColumnComparator(1, CarComparators.getCarNameComparator());
        setColumnComparator(2, CarComparators.getCarPowerComparator());
        setColumnComparator(3, CarComparators.getCarPriceComparator());
    }

}
